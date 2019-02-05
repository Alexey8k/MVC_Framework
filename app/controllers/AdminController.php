<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionIndex()
    {
        $repository = new AutoStoreDb();

        $this->view($repository->getProductsByCategory(""));
    }

    public function actionEditProductGet(int $productId = null)
    {
        $repository = new AutoStoreDb();
        $this->viewBag['categories'] = $repository->getCategories();
        $model = is_null($productId) ? new Product() : $repository->getProduct($productId);
        $this->view($model);
    }

    public function actionEditProductPost(Product $product, string $image = null)
    {
        $repository = new AutoStoreDb();
        $repository->saveProduct($product, $image);
        $this->redirectToAction('index', 'Admin');
    }

    public function actionCreateProduct() {
        $this->redirectToAction('EditProduct', 'Admin');
    }

    public function actionDeleteProduct(int $id) {
        $repository = new AutoStoreDb();
        $repository->deleteProduct($id);
        $this->redirectToAction('Index', 'Admin');
    }


    public function actionOrderList(string $status) {
        $repository = new AutoStoreDb();
        $model = $repository->orderList($status);
        $this->viewBag["status"] = $status;
        $this->view($model);
    }

    public function actionDetailByOrder(int $id) {
        $repository = new AutoStoreDb();
        $model = $repository->getOrder($id);
        $this->view($model);
    }

    public function actionCloseOrder(int $id, string $returnUrl, string $status) {
        $repository = new AutoStoreDb();
        $model = $repository->closeOrder($id);
        $this->redirect($returnUrl);
    }
}