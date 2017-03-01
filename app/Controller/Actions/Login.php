<?php
namespace CDGNG\Controller\Actions;

class Login extends Action
{
    protected function do()
    {
        if ($this->model->users->isExist($this->post['login'])) {
            $user = $this->model->users[$this->post['login']];
            if ($user->checkUnencodedPassword($this->post['password'])) {
                $this->model->users->connected = $user;
                $_SESSION['login'] = $this->model->users->connected->login;
                $_SESSION['password'] = $this->model->users->connected->password;
            }
        }
    }

    protected function checkParameters()
    {
        if (!isset($this->post['login']) or !isset($this->post['password'])) {
            throw new \Exception("Paramètre manquant", 1);
        }
    }
}
