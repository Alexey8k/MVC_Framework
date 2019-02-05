<?php
$layout = 'app/views/Shared/_AdminLayout.php';
$viewBag['title'] = 'Все товары';
/**
 * @var Product[] $model
 */
?>

<h1>Все товары</h1>
<table class="Grid">
    <tr>
        <th>ID</th>
        <th>Наименование</th>
        <th class="NumericCol">Цена</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($model as $item) : ?>
        <tr>
            <td><?= $item->id ?></td>
            <td>
                <?= Html::actionLink($item->name, 'EditProduct', 'Admin', ['productId'=>$item->id]) ?>
            </td>
            <td class="NumericCol">
                <?=
                (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                    ->formatCurrency( $item->price, "USD")
                ?>
            </td>
            <td style="width: 1%">
                <form action="<?= Url::action('DeleteProduct', 'Admin') ?>" method="post">
                    <input type="hidden" name="id" value="<?= $item->id ?>" />
                    <input type="submit" value="Удалить" />
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>
<p>
    <?= Html::actionLink('Добавить новай товар', 'CreateProduct', 'Admin') ?>
</p>