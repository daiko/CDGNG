<?php
namespace CDGNG\Views;

class Statistics extends TwigView
{
    protected function getTemplateFilename()
    {
        return 'results.twig';
    }

    protected function getData()
    {
        $stats = $this->getStatistics();
        $error = array();
        foreach ($stats->calendars as $name => $calendar) {
            $error[$name] = $calendar['calendar']->errors;
        }
        return array(
            'pageTitle' => "CDG : statistiques",
            'actions' => $this->model->actions,
            'modes' => $this->model->modes,
            'errors' => $error,
            'statistics' => $stats,
        );
    }

    public function checkParameters()
    {
        if (!isset($this->post["ics"])) {
            throw new \Exception("Aucun calendrier n'a été sélectionné", 1);
        }

        if (!isset($this->post['startDate'])
            or !isset($this->post['endDate'])
            or !isset($this->post['export'])
        ) {
            throw new \Exception("Paramètre(s) manquant(s)", 1);
        }
    }

    private function getStatistics()
    {
        $tab = explode("-", $this->post["startDate"], 3);
        $dtstart = strtotime($tab[2] . "-" . $tab[1] . "-" . $tab[0]);

        $tab = explode("-", $this->post["endDate"], 3);
        $dtend = mktime(23, 59, 59, $tab[1], $tab[0], $tab[2]);

        switch($this->post["export"]) {
            case "day":
                $stat = new \CDGNG\Statistics\Day($dtstart, $dtend);
                break;
            case "week":
                $stat = new \CDGNG\Statistics\Week($dtstart, $dtend);
                break;
            case "month":
                $stat = new \CDGNG\Statistics\Month($dtstart, $dtend);
                break;
            case "year":
                $stat = new \CDGNG\Statistics\Year($dtstart, $dtend);
                break;
            default:
                $stat = new \CDGNG\Statistics\All($dtstart, $dtend);
                break;
        }
        foreach ($this->post["ics"] as $calName) {
            $calendar = new \CDGNG\Calendar(
                $calName,
                $this->model->calendars[$calName]['url'],
                $this->model->actions,
                $this->model->modes
            );
            $stat->add($calendar);
        }
        return $stat;
    }
}
