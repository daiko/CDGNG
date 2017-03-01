<?php
namespace CDGNG\Controller\Actions;

class DelCalendar extends AdminAction
{
    protected function do()
    {
        $this->model->calendars->remove($this->get['name']);
        $this->model->calendars->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->get['name'])) {
            throw new \Exception("Paramètre manquant", 1);
        }

        if (!$this->model->calendars->isExist($this->get['name'])) {
            throw new \Exception("Le calendrier n'existe pas.", 1);
        }
    }
}
