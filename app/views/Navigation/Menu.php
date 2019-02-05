<?php
/**
 * @var string[] $model
 * @var array $viewBag
 */
?>

<a href="/">Главная</a>

<?php foreach ($model as $value) : ?>
    <?= Html::routeLink($value,
        [
            'controller' => 'Product',
            'action' => 'List',
            'category' => $value,
            'page' => 1
        ],
        [
                'class' => $value == $viewBag['selectedCategory'] ? 'selected' : null
        ]) ?>
<?php endforeach ?>