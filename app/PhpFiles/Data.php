<?php
namespace CDGNG\PhpFiles;

class Data implements \ArrayAccess, \Iterator
{
    protected $filename;
    protected $data;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function read()
    {
        if (!is_file($this->filename)) {
            throw new \Exception("'$this->filename' does not exist", 1);
        }
        $data = array();
        include($this->filename);
        $this->data = $data;
    }

    public function write()
    {
        $fileContent = "<?php\n\$parameters = "
            . var_export($this->parameters)
            . ';\n';

        file_put_contents($this->filename, $fileContent);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
            return;
        }
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function rewind()
    {
        return reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid()
    {
        return isset($this->data[$this->key()]);
    }

    public function next()
    {
        return next($this->data);
    }
}