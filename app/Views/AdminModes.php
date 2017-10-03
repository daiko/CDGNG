<?php
namespace CDGNG\Views;

class AdminModes extends TwigView
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
            'active' => "modes",
            'modes' => $this->model->modes,
            'connectedUser' => $this->model->users->connected,
            'messages' => $messages->getAll(),
        );
    }
}
