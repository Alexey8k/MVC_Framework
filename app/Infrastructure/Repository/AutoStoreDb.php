<?php

class AutoStoreDb extends Repository
{
    private $productMapper;

    private $orderPartialMapper;

    private $orderMapper;

    private $cartLineMapper;

    public function __construct()
    {
        parent::__construct();

        $this->productMapper = function (array $row) : Product {
            $product = new Product();
            $product->id = $row['id'];
            $product->name = $row['name'];
            $product->description = $row['description'];
            $product->price = $row['price'];
            return $product;
        };

        $this->orderPartialMapper = function (array $row) : OrderPartial {
            $orderPartial = new OrderPartial();
            $orderPartial->id = $row['id'];
            $orderPartial->userName = $row['userName'];
            $orderPartial->status = $row['status'];
            $orderPartial->date = new DateTime($row['date']);
            $orderPartial->totalPrice = $row['totalPrice'];
            return $orderPartial;
        };

        $this->orderMapper = function (array $row) : Order {
            $order = new Order();
            $order->id = $row['id'];
            $order->userId = $row['userId'];
            $order->userName = $row['userName'];
            $order->status = $row['status'];
            $order->phone = $row['phone'];
            $order->address = $row['address'];
            $order->city = $row['city'];
            $order->country = $row['country'];
            $order->date = new DateTime($row['date']);
            return $order;
        };

        $this->cartLineMapper = function (array $row) : CartLine {
            $product = new Product();
            $product->id = $row['id'];
            $product->name = $row['name'];
            $product->description = $row['description'];
            $product->category = $row['category'];
            $product->price = $row['price'];

            $cartLine = new CartLine();
            $cartLine->product = $product;
            $cartLine->quantity = $row['quantity'];
            return $cartLine;
        };
    }

    public function login(string $login, string $password, IHashAlgorithm $hashAlgorithm)
    {
        $hash = $hashAlgorithm->getHash($login . '|' . $password);

        return $this->callStoredProcedure('login', [$hash], function (mysqli_result $queryResult) {
            $result = $queryResult->fetch_object();
            $objResult = new stdClass();
            $objResult->result = (int)$result->result;
            unset($result->result);
            $objResult->user = $result->id === null ? null : $result;
            return $objResult;
        });
    }

    public function logout(int $id)
    {
        return $this->link->query("CALL `logout`($id)");
    }

    public function isExistsLogin(string $login)
    {
        return $this->callStoredProcedure('IsExistsLogin', [$login], function (mysqli_result $queryResult) {
            $result = $queryResult->fetch_object();
            settype($result->result, 'int');
            return $result;
        });
    }

    public function registration(string $login, string $password, int $roleId)
    {
        return $this->callStoredProcedure('Registration', [$login,$password,$roleId], function (mysqli_result $queryResult) {
            $result = $queryResult->fetch_object();
            settype($result->result, 'int');
            return $result;
        });
    }

    public function getCategories()
    {
        if (!$queryResult = $this->link->query("SELECT `name` FROM `Category`"))
            return null;

        $result = $queryResult->fetch_all(MYSQLI_ASSOC);
        $queryResult->free();
        return array_map(function ($el) {
            return $el['name'];
        }, $result);
    }

    public function getProductImage(int $id)
    {
        return $this->callStoredProcedure('GetProductImage', [$id], function (mysqli_result $queryResult) {
            return $queryResult->fetch_assoc()['imageData'];
        });
    }

    /**
     * @param string $category
     * @param int $page
     * @param int $count
     * @return Product[]
     */
    public function getProductsByCategoryPager(string $category, int $page, int $count)
    {
        return $this->storedProcedureCall('GetProductsByCategoryPager')
            ->returningResultSet('products', $this->productMapper)
            ->addParam('s', $category)
            ->addParam('i', $page)
            ->addParam('i', $count)
            ->execute()['products'];
    }

    public function getProductsByCategory(string $category) {
        return $this->storedProcedureCall('GetProductsByCategory')
            ->returningResultSet('products', $this->productMapper)
            ->addParam('s', $category)
            ->execute()['products'];
    }

    public function getQuantityByCategory(string $category) : int
    {
        return $this->callStoredProcedure('GetQuantityByCategory', [$category], function (mysqli_result $queryResult) {
            return $queryResult->fetch_array()[0];
        });
    }

    public function getProduct(int $id) : Product
    {
        return $this->callStoredProcedure('GetProduct', [$id], function (mysqli_result $queryResult) {
            $result = $queryResult->fetch_object("Product");
            return $result;
        });
    }

    public function fixOrder(ShippingDetails $shippingDetails, Cart $cart, int $userId = null) {
        $orderId = $this->storedProcedureCall('FixOrder', true)
            ->addParam('i', $userId)
            ->addParam('s', $shippingDetails->name)
            ->addParam('s', $shippingDetails->phone)
            ->addParam('s', $shippingDetails->address)
            ->addParam('s', $shippingDetails->city)
            ->addParam('s', $shippingDetails->country)
            ->execute();

        foreach ($cart->lineCollection as $cartLine)
            $this->storedProcedureCall('AddProductToOrder')
                ->addParam('i', $orderId)
                ->addParam('i', $cartLine->product->id)
                ->addParam('i', $cartLine->quantity)
                ->execute();
    }

    public function saveProduct(Product $product, string $imageData = null) {
        $this->storedProcedureCall('SaveProduct')
            ->addParam('i', $product->id)
            ->addParam('s', $product->name)
            ->addParam('s', $product->description)
            ->addParam('s', $product->category)
            ->addParam('d', $product->price)
            ->addParam('s', $imageData)
            ->execute();
    }

    public function deleteProduct(int $id) {
        $this->storedProcedureCall('DeleteProduct')
            ->addParam('i', $id)
            ->execute();
    }

    public function orderList(string $status) {
        return $this->storedProcedureCall('OrderList')
            ->returningResultSet('orderList', $this->orderPartialMapper)
            ->addParam('s', $status)
            ->execute()['orderList'];
    }

    public function getOrder(int $id) : Order {
        $data = $this->storedProcedureCall('GetOrder')
            ->returningResultSet('order', $this->orderMapper)
            ->returningResultSet('cartLines', $this->cartLineMapper)
            ->addParam('i', $id)
            ->execute();

        $order = $data['order'][0];

        $order->cart->addLines($data['cartLines']);
        return $order;
    }

    public function closeOrder(int $id) {
        $this->storedProcedureCall('CloseOrder')
            ->addParam('i', $id)
            ->execute();
    }
}