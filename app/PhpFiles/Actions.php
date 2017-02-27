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
        $code = $this->formatCode($code);
        $this->checkCode($code);

        $title = filter_var($title, FILTER_SANITIZE_STRING);
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $referent = filter_var($referent, FILTER_SANITIZE_STRING);

        if ($archive !== '1') {
            $archive = '0';
        }

        $this->data[$code] = array(
            'Intitulé' => $title,
            'Description' => $description,
            'Referent' => $referent,
            'Visible' => $archive,
        );
        ksort($this->data);
    }

    private function formatCode($code)
    {
        $code = (int) $code;
        if ($code < 10) {
            return '00' . $code;
        }
        if ($code < 100) {
            return '0' . $code;
        }
        return (string) $code;
    }

    private function checkCode($code)
    {
        if (preg_match('/^[0-9]{3}$/', $code) !== 1) {
            throw new \Exception("Code action mal formatté.", 1);
        }

        if ($this->isExist($this->formatCode($code))) {
            throw new \Exception("L'action existe déjà", 1);
        }
    }
}
