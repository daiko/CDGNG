<?php
namespace CDGNG\Controller\Actions;

class DelMode extends Action
{
    protected function do()
    {
        $this->model->modes->remove($this->get['code']);
        $this->model->modes->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->get['code'])) {
            throw new \Exception("Paramètre manquant.", 1);
        }

        if (!$this->model->modes->isExist($this->get['code'])) {
            throw new \Exception("La modalité n'existe pas.", 1);
        }
    }
}
