<?php

require_once "app/models/PagingInfo.php";

/**
 * Class ListModel
 * @property Product[] products
 * @property PagingInfo pagingInfo
 * @property string currentCategory
 */
class ListModel extends BaseModel
{
    protected $_products;
    protected $_pagingInfo;
    protected $_currentCategory;
}