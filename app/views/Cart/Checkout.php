<?php
$layout = 'app/views/Shared/_CheckoutLayout.php';
$viewBag['title'] = 'Оформление заказа';
/**
 * @var $model CheckoutModel
 */
?>

<div id="checkout">
    <h3 style="margin: 0">Оформление заказа</h3>
    <p>Пожалуйста, введите свои данные, и мы отправим ваши товары прямо сейчас!</p>

    <form method="post" class="form-horizontal">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Имя</label>
            <div class="col-sm-10">
                <input name="name" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label">Телефон</label>
            <div class="col-sm-10">
                <input name="phone" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="address" class="col-sm-2 control-label">Адрес</label>
            <div class="col-sm-10">
                <input name="address" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-sm-2 control-label">Город</label>
            <div class="col-sm-10">
                <input name="city" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="country" class="col-sm-2 control-label">Страна</label>
            <div class="col-sm-10">
                <input name="country" class="form-control" />
            </div>
        </div>
        <?php if (isset(Session::getSession()['user'])) : ?>
            <input type="hidden" name="userId" value="<?= Session::getSession()['user']->id ?>" />" />
        <?php endif ?>
        <div class="form-group">
            <div class="col-sm-7 actionButtons">
                <input type="submit" value="Заказ подтверждаю" />
                <a href="<?= $model->returnUrl ?>">Отмена</a>
            </div>
        </div>
    </form>
</div>