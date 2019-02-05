<?php

class StoredProcedure
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mysqli
     */
    private $link;

    /**
     * @var bool
     */
    private $isFunction;

    /**
     * @var Parameter[]
     */
    private $params = [];

    /**
     * @var callable[]
     */
    private $rowMappers = [];

    private const DEFAULT_KEY = 'ResultSet';

    public function __construct(string $name, mysqli $link, bool $isFunction = false)
    {
        $this->name = $name;
        $this->link = $link;
        $this->isFunction = $isFunction;
    }

    public function returningResultSet(string $paramName, callable $rowMapper = null) {
        $this->rowMappers[$paramName] = $rowMapper;
        return $this;
    }

    public function addParam(string $type, $value) : StoredProcedure {
        $this->params[] = new Parameter($type, $value);
        return $this;
    }

    public function execute() {
        $stmt = $this->link->prepare(
            ($this->isFunction ? 'SELECT' : 'CALL')
            . " `$this->name`(" . implode(',', array_fill(0, count($this->params), '?')) . ")");
        call_user_func_array(array($stmt, 'bind_param'), array_merge(array($this->getTypesString()), $this->getParamValuesByRef()));
        return $this->isFunction ? $this->executeFunction($stmt) : $this->executeProcedure($stmt);
    }

    private function executeFunction(mysqli_stmt $stmt) {
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        return $result;
    }

    private function executeProcedure(mysqli_stmt $stmt) : array {
        $result = [];

        $stmt->execute();
        do {
            $mysqliResult = $stmt->get_result();
            if (!$mysqliResult) continue;
            $key = count($this->rowMappers) != 0 ? array_keys($this->rowMappers)[0] : $this->getDefaultKey(array_keys($result));
            $rowMapper = array_shift($this->rowMappers);
            $result[$key] = [];
            while ($row = $mysqliResult->fetch_assoc()) {
                $result[$key][] = !is_null($rowMapper) ? $rowMapper($row) : $row;
            }
            $mysqliResult->free();
        } while($stmt->more_results() && $stmt->next_result());
        $stmt->close();
        return $result;
    }

    private function getDefaultKey(array $keys, int $postfix = null) : string {
        $defaultKey = StoredProcedure::DEFAULT_KEY . $postfix;
        return key_exists($defaultKey, $keys)
            ? $this->getDefaultKey($keys, is_null($postfix) ? 1 : $postfix++)
            : $defaultKey;
    }

    private function getTypesString() : string {
        return implode(array_map(function (Parameter $param) {
            return $param->type;
        }, $this->params));
    }

    private function getParamValuesByRef(){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = [];
            foreach($this->params as $key => $value)
                $refs[$key] = &$this->params[$key]->getValueByRef();
            return $refs;
        }
        return array_map(function (Parameter $param) {
            return $param->value;
        }, $this->params);
    }
}