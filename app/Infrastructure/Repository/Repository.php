<?php

abstract class Repository
{
    protected $link;

    public function __construct(string $repositoryName = null)
    {
        $connection = $this->getConnectionConfig($repositoryName ?? get_called_class());
        $this->link = new mysqli($connection['host'], $connection['userName'], $connection['password'], $connection['dbName']);
    }

    public function close()
    {
        $this->link->close();
    }

    protected function callStoredProcedure(string $name, array $inArgs, callable $fetchResult)
    {
        $queryStr = "CALL `$name`('" . implode("','", $inArgs) . "')";
        $this->link->real_query($queryStr);
        if (!$queryResult = $this->link->store_result()) return null;

        $result = $fetchResult($queryResult);

        $this->resetNextResults();

        $queryResult->free();

        return $result;
    }

    protected function storedProcedureCall(string $name, bool $isFunction = false)
    {
        return new StoredProcedure($name, $this->link, $isFunction);
    }

    private function resetNextResults()
    {
        if ($this->link->more_results() && $this->link->next_result()) $this->resetNextResults();
        return;
    }

    private function getConnectionConfig(string $repositoryName)
    {
        return ((array)simplexml_load_file('app/Web.config')
            ->mysql->connections->add[0])['@attributes'];
    }
}
