<?php
namespace CDGNG\Controller\Actions;

class AddAction extends AdminAction
{
    protected function do()
    {
        $this->model->actions->add(
            $this->post['code'],
            $this->post['title'],
            $this->post['description'],
            $this->post['referent'],
            $this->post['archive']
        );
        $this->model->actions->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->post['code'])
            or !isset($this->post['title'])
            or !isset($this->post['description'])
            or !isset($this->post['referent'])
        ) {
            throw new \Exception("Paramètre manquant.", 1);
        }

        if (!isset($this->post['archive'])) {
            $this->post['archive'] = 0;
        }
        $this->post['archive'] = intval($this->post['archive']);

        if (preg_match('/^[0-9]{1,3}$/', $this->post['code']) !== 1) {
            throw new \Exception("Code action mal formatté.", 1);
        }

        $this->formatCode();

        $this->post['title'] = filter_var($this->post['title'], FILTER_SANITIZE_STRING);
        $this->post['description'] = filter_var($this->post['description'], FILTER_SANITIZE_STRING);
        $this->post['referent'] = filter_var($this->post['referent'], FILTER_SANITIZE_STRING);

        if ($this->model->actions->isExist($this->post['code'])) {
            throw new \Exception("L'action existe déjà", 1);
        }
    }

    private function formatCode()
    {
        $code = (int) $this->post['code'];
        if ($code < 10) {
            $this->post['code'] = '00' . $code;
            return;
        }
        if ($code < 100) {
            $this->post['code'] = '0' . $code;
            return;
        }
    }
}
