<?php
namespace CDGNG\Controller\Actions;

class AddUser extends AdminAction
{
    protected function do()
    {
        $this->model->users->add(
            $this->post['login'],
            $this->post['password1']
        );
        $this->model->users->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->post['login'])
            or !isset($this->post['password1'])
            or !isset($this->post['password2'])
        ) {
            throw new \Exception("Paramètre manquant", 1);
        }

        $this->post['code'] = filter_var($this->post['login'], FILTER_SANITIZE_STRING);
        $this->post['title'] = filter_var($this->post['password1'], FILTER_SANITIZE_STRING);
        $this->post['description'] = filter_var($this->post['password1'], FILTER_SANITIZE_STRING);

        if ($this->post['password1'] !== $this->post['password2']) {
            throw new \Exception("Les mots de passe sont différents", 1);
        }

        if ($this->model->users->isExist($this->post['login'])) {
            throw new \Exception("L'utilisateur existe déjà", 1);
        }
    }
}
