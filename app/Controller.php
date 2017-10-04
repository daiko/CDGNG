<?php
namespace CDGNG;

class Controller
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function run($post, $get, $session)
    {
        // Connecte l'utilisateur
        if (isset($session['login']) and isset($session['password'])) {
            $this->model->users->connect($session['login'], $session['password']);
        }

        if (isset($get['action'])) {
            $this->doAction($post, $get);
            return;
        }

        $this->shownView($post, $get);
    }

    private function shownView($post, $get)
    {
        if (!isset($get['view']) or empty($get['view'])) {
            $get['view'] = 'Main';
        }

        // Tente d'afficher l'interface d'administration sans être connecté.
        if (substr($get['view'], 0, 5) === 'admin'
            and !$this->model->users->isAdmin()
        ) {
            $get['view'] = 'auth';
        }

        $classView = '\\CDGNG\\Views\\' . ucfirst($get['view']);
        $view = new $classView($post, $get, $this->model);

        try {
            $view->checkParameters();
        } catch (\Exception $e) {
            $messages = new Messages();
            $messages->add('error', $e->getMessage());
            header("Location: ?view=");
            return;
        }
        $view->show();
    }

    private function doAction($post, $get)
    {
        $classAction = '\\CDGNG\\Controller\\Actions\\' . ucfirst($get['action']);

        $action = new $classAction($post, $get, $this->model);

        try {
            $action->execute();
        } catch (\Exception $e) {
            $messages = new Messages();
            $messages->add('error', $e->getMessage());
        }

        if (!isset($get['view'])) {
            $get['view'] = '';
        }
        header("Location: ?view=" . $get['view']);
    }
}
