<?php
namespace CDGNG\Views;

class AdminCalendars extends TwigView
{
    protected function getTemplateFilename()
    {
        return 'admin.twig';
    }

    public function checkParameters()
    {
        if (!$this->model->users->isAdmin()) {
            throw new \Exception("Accès interdit.", 1);
        }
    }

    protected function getData()
    {
        $messages = new \CDGNG\Messages();

        return array(
            'pageTitle' => "CDG : Administration",
            'active' => "calendars",
            'calendars' => $this->model->calendars,
            'connectedUser' => $this->model->users->connected,
            'messages' => $messages->getAll(),
        );
    }
}
