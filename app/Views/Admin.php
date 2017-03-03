<?php
namespace CDGNG\Views;

class Admin extends TwigView
{
    protected function getTemplateFilename()
    {
        return 'admin.twig';
    }

    protected function getData()
    {
        $messages = new \CDGNG\Messages();

        return array(
            'pageTitle' => "CDG : Administration",
            'calendars' => $this->model->calendars,
            'actions' => $this->model->actions,
            'modes' => $this->model->modes,
            'users' => $this->model->users,
            'connectedUser' => $this->model->users->connected,
            'messages' => $messages->getAll(),
        );
    }
}
