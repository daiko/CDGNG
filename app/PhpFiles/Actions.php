<?php
namespace CDGNG\PhpFiles;

class Actions extends Code
{
    public function exportToCsvNoArchive()
    {
        $csv = new \CDGNG\Csv();
        $csv->insert(array('Code', 'Intitulé', 'Description', 'Référent'));
        foreach ($this->data as $code => $action) {
            if ($action['Visible'] === 1) {
                $csv->insert(
                    array(
                        $code,
                        $action['Intitulé'],
                        $action['Description'],
                        $action['Referent']
                    )
                );
            }
        }
        return $csv;
    }

    public function exportToCsvWithArchive()
    {
        $csv = new \CDGNG\Csv();
        $csv->insert(array('Code', 'Intitulé', 'Description', 'Référent'));
        foreach ($this->data as $code => $action) {
            $csv->insert(
                array(
                    $code,
                    $action['Intitulé'],
                    $action['Description'],
                    $action['Referent']
                )
            );
        }
        return $csv;
    }
}
