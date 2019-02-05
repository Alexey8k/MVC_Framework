<div class="validateTips"></div>
<form ng-form-submit name="registrationForm" action="/Account/Registration" method="post" ng-controller="registrationController" novalidate>
    <fieldset>
        <div class="form-group" ng-class="{ 'has-error' : registrationForm.login.$invalid && !registrationForm.login.$pristine}">
            <label>Логин
                <input name="login" ng-model="data.login" class="text ui-widget-content form-control"
                       ng-minlength="loginLength.min" ng-maxlength="loginLength.max" login-valid required />
            </label>
            <div class="help-block" ng-messages="registrationForm.login.$error" ng-if="registrationForm.login.$dirty">
                <span ng-message="loginValid">Данный логин уже используется!</span>
                <span ng-message="required">Это обязательное поле!</span>
                <span ng-message="minlength || maxlength">Логин можед содержать от {{loginLength.min}} до {{loginLength.max}} символов!</span>
            </div>
<!--            <span ng-show="registrationForm.login.$error.loginValid" class="help-block">Данный логин уже используется!</span>-->
<!--            <span ng-show="registrationForm.login.$error.required && !registrationForm.login.$pristine" class="help-block">Это обязательное поле!</span>-->
<!--            <span ng-show="registrationForm.login.$error.minlength || registrationForm.login.$error.maxlength" class="help-block">-->
<!--                Логин можед содержать от {{loginLength.min}} до {{loginLength.max}} символов!-->
<!--            </span>-->
        </div>

        <div class="form-group" ng-class="{ 'has-error' : registrationForm.password.$invalid && !registrationForm.password.$pristine }">
            <label>Пароль
                <input type="password" name="password" ng-model="data.password" class="text ui-widget-content form-control"
                       ng-minlength="passwordLength.min" ng-maxlength="passwordLength.max" required />
            </label>
            <div class="help-block" ng-messages="registrationForm.password.$error" ng-if="registrationForm.password.$dirty">
                <span ng-message="required" class="help-block">Это обязательное поле!</span>
                <span ng-message="minlength || maxlength">
                    Пароль можед содержать от {{loginLength.min}} до {{loginLength.max}} символов!
                </span>
            </div>
<!--            <span ng-show="registrationForm.password.$error.required && !registrationForm.password.$pristine" class="help-block">-->
<!--                Это обязательное поле!-->
<!--            </span>-->
<!--            <span ng-show="registrationForm.password.$error.minlength || registrationForm.password.$error.maxlength" class="help-block">-->
<!--                Пароль можед содержать от {{loginLength.min}} до {{loginLength.max}} символов!-->
<!--            </span>-->
        </div>

        <div class="form-group" ng-class="{ 'has-error' : registrationForm.confirmPassword.$invalid && !registrationForm.confirmPassword.$pristine }">
            <label>Повторите пароль
                <input type="password" name="confirmPassword" ng-model="confirmPassword" class="text ui-widget-content form-control"
                       compare-to="registrationForm.password" required ng-disabled="registrationForm.password.$invalid" />
            </label>
            <div class="help-block" ng-messages="registrationForm.confirmPassword.$error" ng-if="registrationForm.confirmPassword.$dirty">
                <span ng-message="compareTo">Пароли не совпадают!</span>
            </div>
<!--                <span ng-show="registrationForm.confirmPassword.$error.compareTo " class="help-block">Пароли не совпадают!</span>-->
        </div>

        <div class="ui-dialog-buttonpane">
            <input type="button" value="Регистрация" ng-click="registration(registrationForm)"
                   ng-disabled="registrationForm.$invalid" />
        </div>
    </fieldset>
</form>