<?php

class NavigationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionMenu(string $category = null)
    {
        $this->viewBag['selectedCategory'] = $category;
        $model = (new AutoStoreDb())->getCategories();
        $this->partialView($model);
    }
}