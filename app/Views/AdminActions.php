<?php
namespace CDGNG\Views;

class AdminActions extends TwigView
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
            'active' => "actions",
            'actions' => $this->model->actions,
            'connectedUser' => $this->model->users->connected,
            'messages' => $messages->getAll(),
        );
    }
}
