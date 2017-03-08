<?php
namespace CDGNG\Views;

class CsvExecuted extends CsvView
{
    protected function getData()
    {
        $stat = new \CDGNG\Statistics\Realised($this->post["date"]);
        foreach ($this->post["ics"] as $calName) {
            $calendar = new  \CDGNG\Calendar(
                $calName,
                $this->model->calendars[$calName]['url'],
                $this->model->actions,
                $this->model->modes
            );
            $stat->add($calendar);
        }

        $this->csv = $stat->exportAsCsv();
        $this->filename = $stat->title . '_realised.csv';
    }

    public function checkParameters()
    {
        $this->post['codes'] = array('Tous');

        // Todo rendre ça inutile
        $_POST['codes'] = array('Tous');
        if (!isset($this->post["ics"])) {
            throw new \Exception("Aucun calendrier n'a été sélectionné", 1);
        }
    }
}
