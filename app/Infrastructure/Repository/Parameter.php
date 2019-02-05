<?php

/**
 * Class Parameter
 * @property string type
 * @property null value
 */
class Parameter extends BaseModel
{
    public function __construct(string $type, $value)
    {
        $this->_type = $type;
        $this->_value = $value;
    }

    protected $_type;

    protected $_value;

    public function & getValueByRef() {
        return $this->_value;
    }
}