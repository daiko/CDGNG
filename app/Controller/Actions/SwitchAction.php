<?php
namespace CDGNG\Controller\Actions;

class SwitchAction extends AdminAction
{
    protected function do()
    {
        $this->model->actions->switchVisibility($this->get['code']);
        $this->model->actions->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->get['code'])) {
            throw new \Exception("Paramètre manquant", 1);
        }
        $this->get['code'] = filter_var($this->get['code'], FILTER_SANITIZE_STRING);
    }
}
