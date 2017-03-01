<?php
namespace CDGNG\Controller\Actions;

abstract class AdminAction extends Action
{
    public function execute()
    {
        $this->checkAccess();
        parent::execute();
    }

    protected function checkAccess()
    {
        if (!$this->model->users->isAdmin()) {
            throw new \Exception(
                "Vous n'avez pas les droits pour effectuer cette action",
                1
            );
        }
    }
}
