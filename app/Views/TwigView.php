<?php
namespace CDGNG\Views;

abstract class TwigView extends InterfaceView
{
    protected $twig;

    abstract protected function getTemplateFilename();

    public function __construct($post, $get, $model)
    {
        parent::__construct($post, $get, $model);
        $twigLoader = new \Twig_Loader_Filesystem($this->getThemePath());
        $this->twig = new \Twig_Environment($twigLoader);
    }

    protected function getThemePath()
    {
        return 'themes/default/';
    }

    public function show()
    {
        echo $this->twig->render(
            $this->getTemplateFilename(),
            $this->getData()
        );
    }
}
