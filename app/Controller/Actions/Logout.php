<?php
namespace CDGNG\Controller\Actions;

class Logout extends Action
{
    protected function do()
    {
        session_destroy();
    }

    protected function checkParameters()
    {
        // rien, vraiment..
    }
}
