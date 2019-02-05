<?php

/**
 * Class Order
 * @property int id
 * @property string status
 * @property int userId
 * @property string userName
 * @property string phone
 * @property string address
 * @property string city
 * @property string country
 * @property DateTime date
 * @property Cart cart
 */
class Order extends BaseModel
{
    protected $_id;
    protected $_status;
    protected $_userId;
    protected $_userName;
    protected $_phone;
    protected $_address;
    protected $_city;
    protected $_country;
    protected $_date;
    protected $_cart;

    public function __construct()
    {
        $this->_cart = new Cart();
    }
}