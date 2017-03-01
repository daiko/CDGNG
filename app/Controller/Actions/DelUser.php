<?php
namespace CDGNG\Controller\Actions;

class DelUser extends AdminAction
{
    protected function do()
    {
        $this->model->users->remove($this->get['code']);
        $this->model->users->write();
    }

    protected function checkParameters()
    {
        if (!isset($this->get['login'])) {
            throw new \Exception("Paramètre manquant.", 1);
        }

        if (!$this->model->users->isExist($this->get['login'])) {
            throw new \Exception("L'utilisateur n'existe pas.", 1);
        }

        if ($this->model->users->connected->login === $this->get['login']) {
            throw new \Exception(
                "Vous ne pouvez pas vous supprimer vous même. Demandez à un amis.",
                1
            );
        }
    }
}
