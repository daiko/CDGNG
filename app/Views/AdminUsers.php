<?php
namespace CDGNG\Views;

class AdminUsers extends TwigView
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
            'active' => "users",
            'users' => $this->model->users,
            'connectedUser' => $this->model->users->connected,
            'messages' => $messages->getAll(),
        );
    }
}
