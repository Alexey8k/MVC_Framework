<?php

class CartModelBinder implements IModelBinder
{
    private const sessionKey = 'cart';

    function bindModel() : Cart
    {
        $sessionKey = CartModelBinder::sessionKey;

        return Session::getSession()[$sessionKey] ?? (Session::getSession()[$sessionKey] = new Cart());
    }
}