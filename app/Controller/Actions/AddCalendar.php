<?php
namespace CDGNG\Controller\Actions;

class AddCalendar extends Action
{
    protected function do()
    {
        $this->model->calendars->add($this->post['name'], $this->post['url']);
        $this->model->calendars->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->post['name'])
            or !isset($this->post['url'])
        ) {
            throw new \Exception("Paramètre manquant.", 1);
        }

        $this->post['name'] = filter_var($this->post['name'], FILTER_SANITIZE_STRING);
        $this->post['url'] = filter_var($this->post['url'], FILTER_SANITIZE_URL);

        if ($this->model->calendars->isExist($this->post['name'])) {
            throw new \Exception(
                "Le calendrier existe déjà.(" . $this->post['name'] . ")",
                1
            );
        }

        if (!filter_var($this->post['url'], FILTER_VALIDATE_URL)) {
            throw new \Exception("L'URL n'est pas valide.", 1);
        }
    }
}
