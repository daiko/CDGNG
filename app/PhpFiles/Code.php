<?php
namespace CDGNG\PhpFiles;

class Code extends Data
{
    public function isExist($code)
    {
        return array_key_exists($code, $this->data);
    }
}
