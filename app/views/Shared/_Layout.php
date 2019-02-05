<?php
/**
 * @var array $viewBag
 * @var string $renderBody
 */
?>

<!DOCTYPE html>
<html ng-app="storeApp">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewBag['title'] ?></title>
    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/Site.css" rel="stylesheet">
    <script src="/js/lib/jquery.js"></script>
    <script src="/js/lib/jquery-ui.js"></script>
    <script src="/js/lib/angular.js"></script>
    <script src="/js/lib/angular-messages.js"></script>
    <script src="/js/lib/angular-animate.js"></script>
    <script src="/js/lib/angular-sanitize.js"></script>
    <script src="/js/lib/ui-bootstrap-tpls-2.5.0.js"></script>
    <script src="/js/dialogAuthorization.js"></script>
    <script src="/js/dialogCart.js"></script>
    <script src="/js/app.js"></script>
    <script src="/js/controllers/authorizationController.js"></script>
    <script src="/js/controllers/accountController.js"></script>
    <script src="/js/controllers/registrationController.js"></script>
    <script src="/js/controllers/cartController.js"></script>
    <script src="/js/controllers/productController.js"></script>
    <script src="/js/controllers/miniCartController.js"></script>
    <script src="/js/controllers/cartLineController.js"></script>
</head>
<body>
<div style="width: 1024px; margin: auto; background-color: gold;" ng-controller="cartController">
    <div id="header">
        <div class="header-right"  ng-controller="accountController">
            <?php if (isset(Session::getSession()['user'])) : ?>
                <div uib-dropdown dropdown-append-to-body="true">
                    <a href id="login-dropdown" class="login" uib-dropdown-toggle>
                        <?= Session::getSession()['user']->login ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" uib-dropdown-menu aria-labelledby="login-dropdown">
                        <?php if (Session::getSession()['user']->role == 'sa' || Session::getSession()['user']->role == 'admin') : ?>
                            <li role="menuitem"><a href="<?= '/Admin/Index' ?>">Инструмент администратора</a></li>
                        <?php endif ?>
                        <li class="divider"></li>
                        <li role="menuitem">
                            <?= Html::actionLink("Выйти", 'Logout', 'Account', ["returnUrl"=>Routing::getRequestUrl()]) ?>
                        </li>
                    </ul>
                </div>
            <?php else : ?>
                <ul>
                    <li><a ng-click="registrationDialog()">Регистрация</a></li>
                    <li><a ng-click="authorizationDialog()">Вход</a></li>
                </ul>
                <div id="authorization-form" title="Вход">
                    <div ng-include="authorizationView"></div>
                </div>
                <div id="registration-form" title="Регистрация">
                    <div ng-include="registrationView"></div>
                </div>
            <?php endif ?>
        </div>
        <div id="cart-summary" ng-click="openCart()">
            <?php Html::renderAction('Summary', 'Cart') ?>
        </div>
        <div id="mini-cart" title="Корзина">
            <div ng-include="cartView" onload="loaded()"></div>
        </div>

        <div class="title">AUTO Store</div>
    </div>
    <div id="menu">
        <?php Html::renderAction('Menu', 'Navigation') ?>
    </div>
    <div id="content">
        <?= $renderBody ?>
    </div>
</div>
</body>
</html>