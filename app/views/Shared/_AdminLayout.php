<?php
/**
 * @var array $viewBag
 * @var string $renderBody
 */
?>

<html ng-app="adminApp">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewBag['title'] ?></title>
    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/Admin.css" rel="stylesheet">
    <script src="/js/lib/angular.js"></script>
    <script src="/js/lib/angular-messages.js"></script>
    <script src="/js/lib/angular-animate.js"></script>
    <script src="/js/lib/angular-sanitize.js"></script>
    <script src="/js/lib/ui-bootstrap-tpls-2.5.0.js"></script>
    <script src="/js/adminApp.js"></script>
</head>
<body>
<div style="width: 1024px; margin: auto">
    <div id="header">
        <div class="header-right" >
            <a href="/" class="btn btn-sm btn-default">В магазин</a>
            <div style="display: inline-block;">
                <div uib-dropdown dropdown-append-to-body="true">
                    <a href id="login-dropdown" class="login" uib-dropdown-toggle>
                        <?= Session::getSession()['user']->login ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" uib-dropdown-menu aria-labelledby="login-dropdown">
                        <li role="menuitem">
                            <?= Html::actionLink("Все товары", 'Index', 'Admin') ?>
                        </li>
                        <li role="menuitem">
                            <?= Html::actionLink("Новые заказы", 'OrderList', 'Admin', ['status'=>'new']) ?>
                        </li>
                        <li role="menuitem">
                            <?= Html::actionLink("История заказов", 'OrderList', 'Admin', ['status'=>'close']) ?>
                        </li>
                        <li class="divider"></li>
                        <li role="menuitem">
                            <?= Html::actionLink("Выйти", 'Logout', 'Account', ['returnUrl'=>'/']) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="title">Admin tools</div>
    </div>
    <div id="content">
        <?= $renderBody ?>
    </div>
</div>
</body>
</html>