<?php
namespace CDGNG\PhpFiles;

class Modes extends Code
{
    public function exportToCsv()
    {
        $csv = new \CDGNG\Csv();
        $csv->insert(array('Code', 'Intitulé', 'Description'));
        foreach ($this->data as $code => $mode) {
            $csv->insert(
                array(
                    $code,
                    $mode['Intitulé'],
                    $mode['Description']
                )
            );
        }
        return $csv;
    }

    public function add($code, $title, $description)
    {
        $this->data[$code] = array(
            'Intitulé' => $title,
            'Description' => $description,
        );
        ksort($this->data);
    }
}
