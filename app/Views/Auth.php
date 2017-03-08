<?php
namespace CDGNG\Views;

class Auth extends TwigView
{
    protected function getTemplateFilename()
    {
        return 'auth.twig';
    }

    public function checkParameters()
    {
        // Rien.
    }

    protected function getData()
    {
        $messages = new \CDGNG\Messages();

        return array(
            'pageTitle' => "CDG : Authentification",
            'messages' => $messages->getAll(),
        );
    }
}
