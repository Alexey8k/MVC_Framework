<?php
/**
 * @var Product $model
 * @var array $viewBag
 */
$layout = 'app/views/Shared/_AdminLayout.php';
$viewBag['title'] = 'Редактирование ' . $model->name;
?>

<h1>Редактирование <?= $model->name ?></h1>

<form class="form-horizontal" enctype="multipart/form-data" method="post"
      action="<?= Url::action('EditProduct', 'Admin') ?>">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Наименование</label>
        <div class="col-sm-5">
            <input name="name" class="form-control" value="<?= $model->name ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Описание</label>
        <div class="col-sm-5">
            <textarea name="description" cols="40" rows="5" class="form-control"><?= $model->description ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Категория</label>
        <div class="col-sm-5">
            <select name="category" class="form-control">
                <?php foreach ($viewBag['categories'] as $category) : ?>
                    <option
                        <?php if ($category == $model->category) : ?>
                            selected="selected"
                        <?php endif ?>
                        value="<?= $category ?>"><?= $category ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">Цена</label>
        <div class="col-sm-5">
            <input name="price" class="form-control" value="<?= $model->price ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="editor-label col-sm-2 control-label">Изображение</div>
        <div class="editor-field col-sm-5">
            <img height="110" src="<?= Url::action('GetImage', 'Product', ['id'=> $model->id]) ?>" alt="Изображение отсутствует" />
        </div>
    </div>
    <div class="form-group">
        <label for="image" class="col-sm-2 control-label">Загрузить новое изображение</label>
        <div class="col-sm-5">
            <input type="hidden" name="MAX_FILE_SIZE" value="700000" />
            <input type="file" name="image" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <input type="submit" value="Сохранить" class="btn btn-sm btn-primary" />
            <?= Html::actionLink('Отмена', 'Index', 'Admin', ['productId'=>$model->id], ['class'=>"btn btn-sm btn-default"]) ?>
        </div>
    </div>
    <input type="hidden" name="id" value="<?= $model->id ?>" />
</form>