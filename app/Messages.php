<?php
namespace CDGNG;

class Messages
{

    public function getAll()
    {
        $this->initiate();

        $messages = $_SESSION['messages'];
        $_SESSION['messages'] = array();
        return $messages;
    }

    public function add($lvl, $text)
    {
        $this->initiate();
        $_SESSION['messages'][] = array(
            'level' => $lvl,
            'text' => $text,
        );
    }

    protected function initiate()
    {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }
    }
}
