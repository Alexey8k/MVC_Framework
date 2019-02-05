<?php
/**
 * @var $model SummaryModel
 */
?>

<div class="basket-wrap">
    <div class="basket"></div>
    <div class="basket-label">Корзина</div>
    <?php if ($model->totalItems > 0) : ?>
        <span class="total-items">
            <?= $model->totalItems ?>
        </span>
    <?php endif ?>
</div>
