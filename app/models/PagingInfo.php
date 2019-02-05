<?php

/**
 * Class PagingInfo
 * @property int totalItems
 * @property int itemsPerPage
 * @property int currentPage
 * @property int totalPages
 */
class PagingInfo extends BaseModel
{
    protected $_totalItems;
    protected $_itemsPerPage;
    protected $_currentPage;

    public function __get($name)
    {
        return ($name == 'totalPages') ? $this->getTotalPages() : parent::__get($name);
    }

    private function getTotalPages()
    {
        return ceil($this->totalItems / $this->itemsPerPage);
    }

}