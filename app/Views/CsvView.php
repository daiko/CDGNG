<?php
namespace CDGNG\Views;

abstract class CsvView extends InterfaceView
{
    protected $csv;
    protected $filename = "noname.csv";

    public function __construct($post, $get, $model)
    {
        parent::__construct($post, $get, $model);
        $this->csv = new \CDGNG\Csv();
    }

    public function show()
    {
        $this->getData();
        header("Content-type: text/CSV");
        header("Content-disposition: attachment; filename=$this->filename");
        $this->csv->print();
    }
}
