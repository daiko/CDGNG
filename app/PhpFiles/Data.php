<?php
namespace CDGNG\PhpFiles;

class Data implements \ArrayAccess, \Iterator
{
    protected $filename;
    protected $data = array();

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function read()
    {
        if (!is_file($this->filename)) {
            $this->write(); // Créé le fichier si il n'existe pas.
        }
        $data = array();
        include($this->filename);
        $this->data = $data;
    }

    public function write()
    {
        if (file_exists($this->filename) and !is_writable($this->filename)) {
            throw new \Exception(
                "Le fichier '$this->filename' n'est pas accessible en écriture.",
                1
            );
        }

        $fileContent = "<?php\n\$data = "
            . var_export($this->data, true)
            . ";\n";

        if (file_put_contents($this->filename, $fileContent) === false) {
            throw new \Exception(
                "Erreur lors de l'écriture dans le fichier '$this->filename'",
                1
            );
        }
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
