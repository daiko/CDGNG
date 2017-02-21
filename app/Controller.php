<?php
namespace CDGNG;

class Controller
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function run($post)
    {
        $this->model->loadCalendarsList();

        if (!isset($post["action"])) {
            $view = new Views\Main($this->model);
            $view->show();
            return;
        }

        $method = 'action' . $post['action'];
        $this->$method($post);
    }

    protected function actionShow($post)
    {
        $this->checkSelectedFile($post);
        $stat = $this->getStatistics($post);
        $view = new Views\Results($this->model, $stat);
        $view->show();
    }

    protected function actionExport($post)
    {
        $this->checkSelectedFile($post);
        $stat = $this->getStatistics($post);
        $view = new Views\CsvView(
            $stat->title . '.csv',
            $stat->exportAsCsv()
        );
        $view->show();
    }

    protected function actionRealised($post)
    {
        $this->checkSelectedFile($post);
        // TODO Ne plus utilisé $_POST ici... affreux !
        $_POST['codes'] = array('Tous');
        $stat = new Statistics\Realised($post["date"]);
        foreach ($post["ics"] as $calName) {
            $stat->add($this->model->calendars[$calName]);
        }
        $view = new Views\CsvView(
            $stat->title . '_realised.csv',
            $stat->exportAsCsv()
        );
        $view->show();
    }

    protected function actiontableauAction($post)
    {
        if (isset($post["showArchived"])) {
            $csv = $this->model->exportActionsWithArchivedToCsv();
            $filename = 'action+archive.csv';
        }

        if (!isset($post["showArchived"])) {
            $csv = $this->model->exportActionsNoArchivesToCsv();
            $filename = 'action.csv';
        }

        $view = new Views\CsvView($filename, $csv);
        $view->show();
    }

    protected function actiontableauModalite()
    {
        $view = new Views\CsvView('modalites.csv', $this->model->exportModesToCsv());
        $view->show();
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
            $stat->add($this->model->calendars[$calName]);
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
