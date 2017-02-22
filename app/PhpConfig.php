<?php
namespace CDGNG;

class PhpConfig
{
    private $file = "";
    private $parameters = array();

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function read()
    {
        if (!is_file($this->file)) {
            throw new \Exception("'$this->file' does not exist", 1);
        }
        $parameters = array();
        include($this->file);
        $this->parameters = $parameters;
    }

    public function __get($property)
    {
        if (!isset($this->parameters[$property])) {
            throw new \Exception(get_class($this) . "::$property n'existe pas.", 1);
        }
        return $this->parameters[$property];
    }

    public function __set($property, $value)
    {
        if (!isset($this->parameters[$property])) {
            throw new \Exception(get_class($this) . "::$property n'existe pas.", 1);
        }
        $this->parameters[$property] = $value;
    }

    public function write() {
        $fileContent = "<?php\n\$parameters = "
            . var_export($this->parameters)
            . ';\n';

        file_put_contents($this->file, $fileContent);
    }
}
