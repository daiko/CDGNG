<?php
namespace CDGNG\Controller\Actions;

class DelAction extends Action
{
    protected function do()
    {
        $this->model->actions->remove($this->get['code']);
        $this->model->actions->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->get['code'])) {
            throw new \Exception("Paramètre manquant.", 1);
        }

        if (!$this->model->actions->isExist($this->get['code'])) {
            throw new \Exception("L'action' n'existe pas.", 1);
        }
    }
}
