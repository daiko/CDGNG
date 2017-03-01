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
        return array(
            'calendars' => $this->model->calendars,
            'actions' => $this->model->actions,
            'modes' => $this->model->modes,
            'users' => $this->model->users,
        );
    }
}
