<?php
namespace CDGNG;

class Controller
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function run($post, $get, $session)
    {
        if (isset($session['login']) and isset($session['password'])) {
            $this->model->users->connect($session['login'], $session['password']);
        }

        if (isset($get['action'])) {

            $classAction = '\\CDGNG\\Controller\\Actions\\' . ucfirst($get['action']);

            $action = new $classAction($post, $get, $this->model);

            try {
                $action->execute();
            } catch (\Exception $e) {
                $messages = new Messages();
                $messages->add('error', $e->getMessage());
            }

            if (!isset($get['view'])) {
                $get['view'] = '';
            }
            header("Location: ?view=" . $get['view']);
            return;
        }

        if (!isset($get['view'])) {
            $get['view'] = 'Main';
        }

        $method = 'view' . ucfirst($get['view']);
        if (!method_exists($this, $method)) {
            $method = 'viewMain';
        }

        try {
            $view = $this->$method($post);
        } catch (\Exception $e) {
            $messages = new Messages();
            $messages->add('error', $e->getMessage());
            header("Location: ?view=");
            return;
        }
        $view->show();
    }

    protected function viewMain()
    {
        return new Views\Main($this->model);
    }

    protected function viewAdmin()
    {
        if (!$this->model->users->isAdmin()) {
            return new Views\Auth($this->model);
        }
        return new Views\Admin($this->model);
    }

    protected function viewStatistics($post)
    {
        $this->checkSelectedFile($post);
        $stat = $this->getStatistics($post);
        return new Views\Results($this->model, $stat);
    }

    protected function viewCsvActions($post)
    {
        if (isset($post["archives"]) and $post["archives"] === '1') {
            $csv = $this->model->actions->exportToCsvWithArchive();
            $filename = 'action+archive.csv';
            return new Views\CsvView($filename, $csv);
        }

        $csv = $this->model->actions->exportToCsvNoArchive();
        $filename = 'action.csv';

        return new Views\CsvView($filename, $csv);
    }

    protected function viewCsvModes()
    {
        return new Views\CsvView(
            'modalites.csv',
            $this->model->modes->exportToCsv()
        );
    }

    protected function viewCsvExecuted($post)
    {
        $this->checkSelectedFile($post);
        // TODO Ne plus utilisé $_POST ici... affreux !
        $_POST['codes'] = array('Tous');
        $stat = new Statistics\Realised($post["date"]);
        foreach ($post["ics"] as $calName) {
            $calendar = new Calendar(
                $this->model->calendars[$calName]['url'],
                $this->model->actions,
                $this->model->modes
            );
            $stat->add($calendar);
        }
        $view = new Views\CsvView(
            $stat->title . '_realised.csv',
            $stat->exportAsCsv()
        );
        $view->show();
    }

    protected function viewCsvStatistics($post)
    {
        $this->checkSelectedFile($post);
        $stat = $this->getStatistics($post);
        return new Views\CsvView(
            $stat->title . '.csv',
            $stat->exportAsCsv()
        );
    }

    private function getStatistics($post)
    {
        $tab = explode("-", $post["startDate"], 3);
        $dtstart = strtotime($tab[2] . "-" . $tab[1] . "-" . $tab[0]);

        $tab = explode("-", $post["endDate"], 3);
        $dtend = mktime(23, 59, 59, $tab[1], $tab[0], $tab[2]);

        switch($post["export"]) {
            case "day":
                $stat = new Statistics\Day($dtstart, $dtend);
                break;
            case "week":
                $stat = new Statistics\Week($dtstart, $dtend);
                break;
            case "month":
                $stat = new Statistics\Month($dtstart, $dtend);
                break;
            case "year":
                $stat = new Statistics\Year($dtstart, $dtend);
                break;
            default:
                $stat = new Statistics\All($dtstart, $dtend);
                break;
        }
        foreach ($post["ics"] as $calName) {
            $calendar = new Calendar(
                $this->model->calendars[$calName]['url'],
                $this->model->actions,
                $this->model->modes
            );
            $stat->add($calendar);
        }
        return $stat;
    }

    private function checkSelectedFile($post)
    {
        if (!isset($post["ics"])) {
            throw new \Exception("Aucun calendrier n'a été sélectionné", 1);
        }
    }
}
