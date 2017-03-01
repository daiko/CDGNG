<?php
namespace CDGNG\Controller\Actions;

abstract class Action
{
    protected $post;
    protected $get;
    protected $model;

    public function __construct($post, $get, $model)
    {
        $this->post = $post;
        $this->get = $get;
        $this->model = $model;
    }

    public function execute()
    {
        $this->checkParameters();
        $this->do();
    }

    abstract protected function do();
    abstract protected function checkParameters();
}
