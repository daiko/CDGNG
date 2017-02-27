<?php
namespace CDGNG\PhpFiles;

class Calendars extends Code
{
    public function add($name, $url)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if ($name === '') {
            throw new \Exception("No name for calendar.", 1);
        }

        $this->data[$name] = array('url' => $url);
        ksort($this->data);
    }

    public function checkUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("L'URL n'est pas valide.", 1);
        }
    }
}
