<?php
namespace CDGNG\PhpFiles;

class Config extends Data
{
    public function __get($property)
    {
        if (!isset($this->data[$property])) {
            throw new \Exception(get_class($this) . "::$property n'existe pas.", 1);
        }
        return $this->data[$property];
    }

    public function __set($property, $value)
    {
        if (!isset($this->data[$property])) {
            throw new \Exception(get_class($this) . "::$property n'existe pas.", 1);
        }
        $this->data[$property] = $value;
    }
}
