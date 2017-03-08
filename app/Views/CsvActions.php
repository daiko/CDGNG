<?php
namespace CDGNG\Views;

class CsvActions extends CsvView
{
    protected function getData()
    {
        $this->filename = 'actions.csv';
        if ($this->post["archives"] === '1') {
            $this->csv = $this->model->actions->exportToCsvWithArchive();
            return;
        }
        $this->csv = $this->model->actions->exportToCsvNoArchive();
    }

    public function checkParameters()
    {
        if (!isset($this->post["archives"])) {
            $this->post['archives'] = 0;
        }
    }
}
