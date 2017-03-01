<?php
namespace CDGNG\PhpFiles;

class Users extends Code
{
    public $connected = null;

    public function connect($login, $password)
    {
        if ($this->checkCredentials($login, $password)) {
            $this->connected = $this->data[$login];
            return true;
        }
        $this->connnected = null;
        return false;
    }

    public function add($login, $password)
    {
        $user = new \CDGNG\User($login);
        $user->setPassword($password);
        $this->data[$login] = $user;
    }

    public function checkCredentials($login, $password)
    {
        if ($this->isExist($login)
            and $this->data[$login]->checkEncodedPassword($password)
        ) {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        if (is_null($this->connected)) {
            return false;
        }
        return true;
    }

    public function read()
    {
        parent::read();
        foreach ($this->data as $login => $user) {
            $this->data[$login] = new \CDGNG\User($user['login'], $user['password']);
        }
    }

    public function write()
    {
        // Créé le compte par défaut si aucun compte n'existe.
        if (empty($this->data)) {
            $user = new \CDGNG\User('admin', '');
            $user->setPassword('admin');
            $this->data['admin'] = $user;
        }

        foreach ($this->data as $login => $user) {
            $this->data[$login] = $user->toArray();
        }
        parent::write();
    }
}
