<?php

abstract class BaseModel implements JsonSerializable
{
    public function __get($name)
    {
        $nameFromPrefix = $this->addPrefix($name);
        if (!property_exists($this, $nameFromPrefix))
            $this->propertyError($name);
        return $this->$nameFromPrefix;
    }

    public function __set($name, $value)
    {
        $nameFromPrefix = $this->addPrefix($name);
        if (!property_exists($this, $nameFromPrefix))
            $this->propertyError($name);
        $this->$nameFromPrefix = $value;
    }

    public function __isset($name)
    {
        $name = $this->addPrefix($name);
        return isset($this->$name);
    }

    public function __unset($name)
    {
        $name = $this->addPrefix($name);
        unset($this->$name);
    }

    protected function addPrefix(string $name)
    {
        return "_$name";
    }

    function jsonSerialize()
    {
        $array = get_object_vars($this);
        return array_combine(
            array_map(function ($el) {
                return ltrim($el, '_');
            }, array_keys($array)),
            array_map(function ($el) {
                return is_array($el) ? array_values($el) : $el;
            }, array_values($array)));
    }

    protected function propertyError(string $propertyName)
    {
        user_error('Класс "' . get_called_class() . "\" не содержит свойство $propertyName.");
    }
}