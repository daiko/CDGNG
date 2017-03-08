<?php
namespace CDGNG\Views;

class CsvModes extends CsvView
{
    protected function getData()
    {
        $this->filename = 'modalites.csv';
        $this->csv = $this->model->modes->exportToCsv();
    }

    public function checkParameters()
    {
        // Rien.
    }
}
