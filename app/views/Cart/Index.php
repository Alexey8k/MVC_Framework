<?php
/**
 * @var $model CartModel
 */
?>
<div ng-controller="miniCartController">
    <table style="width: 90%; margin:auto; font-size: 1em;">
        <thead>
        <tr>
            <th style="text-align: center">Количество</th>
            <th style="text-align: left">Наименование</th>
            <th style="text-align: right">Цена</th>
            <th style="text-align: right">Итоговая цена</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model->cart->lineCollection as $line) : ?>
            <tr ng-controller="cartLineController">
                <td style="text-align: center">
                    <div class="cart-amount">
                        <form ng-submit="changeQuantity(data)">
                            <button type="button" ng-click="amountMinus($event)" class="cart-amount-qnt-btn" ><span>-</span></button>
                            <input type="text" ng-model="data.quantity"  class="cart-amount-input-text" value="<?= $line->quantity ?>" mp-value-copy />
                            <button type="button" ng-click="amountPlus($event)" class="cart-amount-qnt-btn" ><span>+</span></button>
                            <input type="hidden" id="id" ng-model="data.id" name="id" value="<?= $line->product->id ?>" mp-value-copy />
                            <input type="hidden" id="returnUrl" ng-model="data.returnUrl" name="returnUrl" value="<?= $model->returnUrl ?>" mp-value-copy />
                        </form>

                    </div>
                </td>
                <td style="text-align: left"><?= $line->product->name ?></td>
                <td style="text-align: right">
                    <?= (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                        ->formatCurrency(  $line->product->price, "USD") ?>
                </td>
                <td style="text-align: right">
                    <?= (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                        ->formatCurrency( $line->quantity * $line->product->price, "USD") ?>
                </td>
                <td style="width: 1%">
                    <form ng-submit="changeQuantity(data)">
                        <input type="hidden" ng-model="data.id" value="<?= $line->product->id ?>" mp-value-copy />
                        <input type="hidden" ng-model="data.returnUrl" value="<?= $model->returnUrl ?>" mp-value-copy />
                        <input class="actionButtons" type="button" value="Удалить" ng-click="removeLine($event)" />
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" align="right">Итого:</td>
            <td align="right">
                <?=
                (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                    ->formatCurrency( $model->cart->computeTotalValue(), "USD")
                ?>
            </td>
        </tr>
        </tfoot>
    </table>
    <p align="center" class="actionButtons">
        <input type="button" value="Продолжить покупки" ng-click="closeMiniCart()" />
        <?= Html::actionLink("Оформить заказ", 'Checkout', null, ["returnUrl"=>$model->returnUrl]) ?>
    </p>
</div>

