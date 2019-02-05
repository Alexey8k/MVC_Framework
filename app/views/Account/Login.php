<?php
/**
 * @var string $model
 */
?>

<div class="validateTips"></div>
<div ng-controller="authorizationController">
    <label>
        Логин:
        <input name="login" type="text" ng-model="data.login" class="text ui-widget-content" />
    </label>
    <label>
        Пароль:
        <input name="password" type="password" ng-model="data.password" class="text ui-widget-content" />
    </label>
    <form ng-form-submit name="authorizationForm" action="/Account/Authorization">
        <input type="hidden" name="returnUrl" value="<?= $model ?>"/>
        <div class="ui-dialog-buttonpane">
            <input type="button" value="Вход" ng-click="authorization(authorizationForm)" />
        </div>
    </form>
</div>
