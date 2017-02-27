<?php
namespace CDGNG\PhpFiles;

class Calendars extends Code
{
    public function add($name, $url)
    {
        $this->data[$name] = array('url' => $url);
        ksort($this->data);
    }
}
