<?php
namespace CDGNG\views;

abstract class InterfaceView
{
    protected $model;
    protected $get;
    protected $post;

    public function __construct($post, $get, $model)
    {
        $this->model = $model;
        $this->post = $post;
        $this->get = $get;
    }

    abstract public function show();
    abstract public function checkParameters();
    abstract protected function getData();
}
