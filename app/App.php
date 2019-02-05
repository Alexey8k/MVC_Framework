<?php

abstract class App
{
    public static function start()
    {
        ModelBinders::getBinders()['Cart'] = new CartModelBinder();
        InterceptorManager::addHandler(new AdminInterceptor());
    }
}