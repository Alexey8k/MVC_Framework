<?php
/**
 * @var Order $model
 * @var array $viewBag
 */
$layout = 'app/views/Shared/_AdminLayout.php';
$viewBag['title'] = 'Подробности заказа';
?>

<div>
    <h2>Подробности заказа</h2>

    <table class="table table-dark">
        <tbody>
        <tr>
            <td colspan="2"><h4>Заказ</h4></td>
        </tr>
        <tr>
            <td>ID:</td>
            <td><?= $model->id ?></td>
        </tr>
        <tr>
            <td>Статус:</td>
            <td><?= $model->status ?></td>
        </tr>
        <tr>
            <td>Дата:</td>
            <td><?= $model->date->format('d-m-Y H:i:s') ?></td>
        </tr>
        <tr>
            <td colspan="2"><h4>Пользователь</h4></td>
        </tr>
        <tr>
            <td>ID пользователя:</td>
            <td><?= $model->userId ?></td>
        </tr>
        <tr>
            <td>Имя пользователь:</td>
            <td><?= $model->userName ?></td>
        </tr>
        <tr>
            <td>Адресс:</td>
            <td><?= $model->address ?></td>
        </tr>
        <tr>
            <td>Горогд:</td>
            <td><?= $model->city ?></td>
        </tr>
        <tr>
            <td>Страна:</td>
            <td><?= $model->country ?></td>
        </tr>
        </tbody>
    </table>

    <h3>Товары</h3>

    <table style="width: 90%; margin:auto; font-size: 1em;" class="table">
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
            <tr>
                <td style="text-align: center">
                    <?= $line->quantity ?>
                </td>
                <td style="text-align: left"><?= $line->product->name ?></td>
                <td style="text-align: right">
                    <?=
                    (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                        ->formatCurrency( $line->product->price, "USD")
                    ?>
                </td>
                <td style="text-align: right">
                    <?=
                    (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                        ->formatCurrency( $line->quantity * $line->product->price, "USD")
                    ?>
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
                    ->formatCurrency( $model->cart->totalValue, "USD")
                ?>
            </td>
        </tr>
        </tfoot>
    </table>

</div>