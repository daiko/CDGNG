<?php
namespace CDGNG\Views;

class Main extends TwigView
{
    protected function getTemplateFilename()
    {
        return 'main.twig';
    }

    protected function getData()
    {
        return array(
            'pageTitle' => "CDG",
            'actions' => $this->model->actions,
            'modes' => $this->model->modes,
            'calendars' => $this->model->calendars,
            'connectedUser' => $this->model->users->connected,
        );
    }
}
