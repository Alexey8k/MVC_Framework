<?php

/**
 * Class OrderPartial
 * @property int id
 * @property string status
 * @property string userName
 * @property DateTime date
 * @property double totalPrice
 */
class OrderPartial extends BaseModel
{
    protected $_id;
    protected $_status;
    protected $_userName;
    protected $_date;
    protected $_totalPrice;
}