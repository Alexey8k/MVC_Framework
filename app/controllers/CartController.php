<?php

class CartController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionIndex(Cart $cart, string $returnUrl)
    {
        $model = new CartModel();
        $model->cart = $cart;
        $model->returnUrl = $returnUrl;
        $this->partialView($model);
    }

    public function actionIndex1()
    {
        $this->partialView();
    }

    public function actionIndexA()
    {
        $this->partialView();
    }

    public function actionGetCart(Cart $cart)
    {
        echo json_encode($cart);
    }

    public function actionAddToCart(Cart $cart, int $id, string $returnUrl)
    {
        $cart->addItem((new AutoStoreDb())->getProduct($id), 1);
        $this->redirectToAction('IndexA', 'Cart', ['returnUrl'=>$returnUrl]);
    }

    public function actionSummary(Cart $cart)
    {
        $model = new SummaryModel();
        $model->totalItems = array_sum(array_map(function (CartLine $el) {
            return $el->quantity;
        },$cart->lineCollection));
        $model->totalValue = $cart->computeTotalValue();
        $this->partialView($model);
    }

    public function actionChangeQuantity(Cart $cart, int $id, int $quantity, string $returnUrl)
    {
        $cart->changeQuantity($id, $quantity);
        $result = new stdClass();
        $result->totalCount = $cart->totalCount;
        echo json_encode($result);

    }

    public function actionChangeQuantity1(Cart $cart, int $id, int $quantity)
    {
        $cart->changeQuantity($id, $quantity);

        $this->redirectToAction("GetCart", "Cart");
    }


    public function actionCheckoutGet(string $returnUrl) {
        $model = new CheckoutModel();
        $model->returnUrl = $returnUrl;
        $this->view($model);
    }

    public function actionCheckoutPost(Cart $cart, ShippingDetails $shippingDetails, int $userId = null) {
        $repository = new AutoStoreDb();
        $repository->fixOrder($shippingDetails, $cart, $userId);
        $cart->clear();
        $this->redirectToAction("Completed", "Cart");
    }

    public function actionCompleted() {
        $this->view();
    }
}