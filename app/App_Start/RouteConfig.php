<?php

class RouteConfig
{
    public static function registerRoutes(RouteCollection $routes)
    {

        $routes->mapRoute('',
            [
                'controller' => 'Product',
                'action' => 'List',
                'category' => (string)null,
                'page' => 1
            ]);

        $routes->mapRoute("Page{page}",
            [
                'controller' => 'Product',
                'action' => 'List',
                'category' => (string)null
            ],
            ['page' => "\d+" ]);

        $routes->mapRoute('{category}',
            [
                'controller' => 'Product',
                'action' => 'List',
                'page' => 1
            ]);

        $routes->mapRoute('{category}/Page{page}',
            [
                'controller' => 'Product',
                'action' => 'List'
            ],
            ['page' => "\d+" ]);

        $routes->mapRoute('{controller}/{action}');
    }
}