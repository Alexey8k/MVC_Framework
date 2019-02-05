<?php
/**
 * @var Product $model
 */
?>
<div class="item" ng-controller="productController">
    <img height="110" src="<?= Url::action("GetImage", "Product", ['id' => $model->id]) ?>" />

    <h3><?= $model->name ?></h3>

    <div>
        <?= $model->description ?>
    </div>

    <form ng-submit="addToCart()">
        <input type="hidden" ng-model="data.id" value="<?= $model->id ?>" mp-value-copy />
        <input type="hidden" ng-model="data.returnUrl" value="<?= Routing::getRequestUrl() ?>" mp-value-copy />
        <input type="submit" value="Купить" />
    </form>

    <h4><?= (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))->formatCurrency( $model->price, "USD") ?></h4>

</div>