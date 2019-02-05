<?php

class AdminInterceptor extends HandlerInterceptor
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function handler(): bool
    {
        if (Session::getSession()['user']->role == 'sa' || Session::getSession()['user']->role == 'admin')
            return true;
        header('Location: /');
        return false;
    }
}