<?php
namespace CDGNG\Views;

class CsvStatistics extends CsvView
{
    protected function getData()
    {
        $stat = $this->getStatistics();
        $this->filename = $stat->title . '.csv';
        $this->csv = $stat->exportAsCsv();
    }

    public function checkParameters()
    {
        if (!isset($this->post["ics"])) {
            throw new \Exception("Aucun calendrier n'a été sélectionné", 1);
        }

        if (!isset($this->post['startDate'])
            or !isset($this->post['endDate'])
            or !isset($this->post['export'])
            or !isset($this->post['codes'])
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
                $stat = new \CDGNG\Statistics\Day($dtstart, $dtend, $this->post['codes']);
                break;
            case "week":
                $stat = new \CDGNG\Statistics\Week($dtstart, $dtend, $this->post['codes']);
                break;
            case "month":
                $stat = new \CDGNG\Statistics\Month($dtstart, $dtend, $this->post['codes']);
                break;
            case "year":
                $stat = new \CDGNG\Statistics\Year($dtstart, $dtend, $this->post['codes']);
                break;
            default:
                $stat = new \CDGNG\Statistics\All($dtstart, $dtend, $this->post['codes']);
                break;
        }
        foreach ($this->post["ics"] as $calName) {
            $calendar = new  \CDGNG\Calendar(
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
