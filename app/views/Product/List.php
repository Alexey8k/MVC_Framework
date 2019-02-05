<?php
$layout = 'app/views/Shared/_Layout.php';
$viewBag['title'] = 'AUTOStore';
/**
 * @var ListModel $model
 */
?>
<!--<div class="wrapper">-->
    <?php for ($i = 0; $i < count($model->products); $i++) : ?>
        <?php if ($i % 3 == 0 || $i == 0) : ?>
           <div class="wrapper">
        <?php endif ?>
        <?php Html::renderPartial('ProductSummary', $model->products[$i]) ?>
        <?php if (($i + 1) % 3 == 0 && $i != 0 || $i + 1 == count($model->products)) : ?>
            </div>
        <?php endif ?>
    <?php endfor ?>
<!--</div>-->

<div class="pager">
    <?= Html::pageLinks($model->pagingInfo, function ($i) use ($model) {
        return Url::action("List", "Product", ['page'=>$i,'category'=>$model->currentCategory]);
    }) ?>
</div>