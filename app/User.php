<?php
namespace CDGNG;

class User
{
    public $login = '';
    public $password = '';

    public function __construct($login, $password = '')
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function checkUnencodedPassword($passwordToCheck)
    {
        return password_verify($passwordToCheck, $this->password);
    }

    public function checkEncodedPassword($passwordToCheck)
    {
        if ($this->password === $passwordToCheck) {
            return true;
        }
        return false;
    }

    public function setPassword($unencodedPassword)
    {
        $this->password = $this->encodePassword($unencodedPassword);
    }

    public function toArray()
    {
        return array(
            'login' => $this->login,
            'password' => $this->password,
        );
    }

    protected function encodePassword($unencodedPassword)
    {
        return password_hash($unencodedPassword, PASSWORD_DEFAULT);
    }
}
