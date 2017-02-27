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
        $this->checkCode($code);
        $title = filter_var($title, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);

        $this->data[$code] = array(
            'Intitulé' => $title,
            'Description' => $description,
        );
        ksort($this->data);
    }

    private function checkCode($code)
    {
        if (preg_match('/^[A-Z]$/', $code) !== 1) {
            throw new \Exception("Code mal formatté.", 1);
        }

        if ($this->isExist($code)) {
            throw new \Exception("Le code existe déjà", 1);
        }
    }
}
