<?php
/**
 * @var OrderPartial[] $model
 * @var array $viewBag
 */
$layout = 'app/views/Shared/_AdminLayout.php';
$viewBag['title'] = $viewBag['status'] == 'new' ? 'Новые заказы' : 'История заказов';
?>

<h1>
    <?= $viewBag['status'] == 'new' ? 'Новые заказы' : 'История заказов' ?>
</h1>
<table class="Grid">
    <tr>
        <th>ID</th>
        <th>Заказчик</th>
        <th>Дата</th>
        <th class="NumericCol">Сумма</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($model as $item) : ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->userName ?></td>
            <td><?= $item->date->format('d-m-Y H:i:s') ?></td>
            <td class="NumericCol">
                <?=
                (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                    ->formatCurrency( $item->totalPrice, "USD")
                ?>
            </td>
            <td style="width: 1%; white-space: nowrap;">
                <a href="<?= '/Admin/DetailByOrder?id=' . $item->id ?>" class="btn btn-sm btn-primary">Подробней</a>
                <?php if ($viewBag['status'] == 'new') : ?>
                    <form action="<?= Url::action('CloseOrder', 'Admin') ?>" method="post" style="display: inline-block;">
                    <input type="hidden" name="id" value="<?= $item->id ?>" />
                    <input type="hidden" name="returnUrl" value="<?= Routing::getRequestUrl() ?>" />
                    <input type="hidden" name="status" value="<?= $viewBag['status'] ?>" />
                    <input type="submit" value="Закрыть" class="btn btn-sm btn-default" />
                    </form>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach ?>
</table>