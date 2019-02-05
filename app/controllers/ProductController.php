<?php

class ProductController extends Controller
{
    private const _pageSize = 2;

    public function __construct()
    {
        parent::__construct();
    }

    public function actionList(string $category, int $page = 1)
    {
        //$this->actionGetImage(8);
        $repository = new AutoStoreDb();

        $model = new ListModel();
        $model->currentCategory = $category;
        $model->products = $repository->getProductsByCategoryPager($category, $page, ProductController::_pageSize);

        $pagingInfo = new PagingInfo();
        $pagingInfo->totalItems = $repository->getQuantityByCategory($category);
        $pagingInfo->currentPage = $page;
        $pagingInfo->itemsPerPage = ProductController::_pageSize;

        $model->pagingInfo = $pagingInfo;

        $this->view($model);
    }

    public function actionGetImage(int $id)
    {
        $repository = new AutoStoreDb();
        $data = $repository->getProductImage($id);

        //echo "get image ------- $data<br>";

        $this->imageFile($data);
    }
}