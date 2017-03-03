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

    public function add($code, $title, $description, $referent, $archive)
    {
        $this->data[$code] = array(
            'Intitulé' => $title,
            'Description' => $description,
            'Referent' => $referent,
            'Visible' => $archive,
        );
        ksort($this->data);
    }

    public function switchVisibility($code)
    {
        if ($this->data[$code]['Visible'] === 1) {
            $this->data[$code]['Visible'] = 0;
            return;
        }
        $this->data[$code]['Visible'] = 1;
        return;
    }
}
