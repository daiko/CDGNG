<?php
namespace CDGNG\Controller\Actions;

class AddMode extends Action
{
    protected function do()
    {
        $this->model->modes->add(
            $this->post['code'],
            $this->post['title'],
            $this->post['description']
        );
        $this->model->modes->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->post['code'])
            or !isset($this->post['title'])
            or !isset($this->post['description'])
        ) {
            throw new \Exception("Paramètre manquant", 1);
        }

        $this->post['code'] = filter_var($this->post['code'], FILTER_SANITIZE_STRING);
        $this->post['title'] = filter_var($this->post['title'], FILTER_SANITIZE_STRING);
        $this->post['description'] = filter_var($this->post['description'], FILTER_SANITIZE_STRING);

        $this->checkCode($this->post['code']);
    }


    private function checkCode($code)
    {
        if (preg_match('/^[A-Z]$/', $code) !== 1) {
            throw new \Exception("Code mal formatté.", 1);
        }

        if ($this->model->modes->isExist($code)) {
            throw new \Exception("Le code existe déjà", 1);
        }
    }
}
