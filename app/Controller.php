<?php
namespace CDGNG;

class Controller
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function run($post, $get)
    {
        if (isset($get['action'])) {
            $method = 'action' . ucfirst($get['action']);
            if (method_exists($this, $method)) {
                $this->$method($post, $get);
            }

            header("Location: ?view=" . $get['view']);
            return;
        }

        $view = new Views\Main($this->model);

        if (isset($get['view'])) {
            $method = 'view' . ucfirst($get['view']);
            if (method_exists($this, $method)) {
                $view = $this->$method($post);
            }
        }
        $view->show();
    }

    protected function viewAdmin()
    {
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

    protected function actionDelCalendar($post, $get)
    {
        if (!isset($get['name'])) {
            return;
        }
        $this->model->calendars->remove($get['name']);
        $this->model->calendars->write();
    }

    protected function actionAddCalendar($post, $get)
    {
        if (!isset($post['name']) or !isset($post['url'])) {
            return;
        }

        $this->model->calendars->add($post['name'], $post['url']);
        $this->model->calendars->write();
    }

    protected function actionDelMode($post, $get)
    {
        if (!isset($get['code'])) {
            return;
        }
        $this->model->modes->remove($get['code']);
        $this->model->modes->write();
    }

    protected function actionAddMode($post, $get)
    {
        if (!isset($post['code'])
            or !isset($post['title'])
            or !isset($post['description'])
        ) {
            return;
        }

        $this->model->modes->add($post['code'], $post['title'], $post['description']);
        $this->model->modes->write();
    }

    protected function actionDelAction($post, $get)
    {
        if (!isset($get['code'])) {
            return;
        }
        $this->model->actions->remove($get['code']);
        $this->model->actions->write();
    }

    protected function actionAddAction($post, $get)
    {
        if (!isset($post['code'])
            or !isset($post['title'])
            or !isset($post['description'])
            or !isset($post['referent'])
        ) {
            return;
        }

        if (!isset($post['archive'])) {
            $post['archive'] = 0;
        }

        $this->model->actions->add(
            $post['code'],
            $post['title'],
            $post['description'],
            $post['referent'],
            $post['archive']
        );
        $this->model->actions->write();
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
            throw new \Exception("Aucun fichier n'a été sélectionné", 1);
        }
    }
}
