<?php

/**
 * Class Cart
 * @property CartLine[] lineCollection
 * @property int totalCount
 * @property double totalValue
 */
class Cart extends BaseModel
{
    /**
     * @var CartLine[]
     */
    protected $_lineCollection = [];

    public function addItem(Product $product, int $quantity)
    {
        $cartLine = $this->_lineCollection[$product->id] ?? null;
        if (is_null($cartLine))
        {
            $cartLine = new CartLine();
            $cartLine->product = $product;
            $cartLine->quantity = $quantity;
            $this->_lineCollection[$product->id] = $cartLine;
        }
        else {
            $cartLine->quantity += $quantity;
        }
    }

    public function addLines(array $cartLines) {
        $this->_lineCollection = array_merge($this->_lineCollection, $cartLines);
    }

    public function __get($name)
    {
        if ($name === 'totalCount') return $this->getTotalCount();
        if ($name === 'totalValue') return $this->computeTotalValue();
        return parent::__get($name);
    }

    private function getTotalCount() : int
    {
        return array_sum(array_map(function (CartLine $el) { return $el->quantity; },$this->_lineCollection));
    }

    public function changeQuantity(int $id, int $quantity)
    {
        if ($quantity === 0)
            unset($this->_lineCollection[$id]);
        else
            $this->_lineCollection[$id]->quantity = $quantity;
    }

    public function removeLine(Product $product)
    {
        unset($this->_lineCollection[$product->id]);
    }

    public function computeTotalValue()
    {
        return array_sum(array_map(function (CartLine $el) { return $el->product->price * $el->quantity; }, $this->_lineCollection));
    }

    public function clear()
    {
        unset($this->_lineCollection);
    }

    function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), ['totalCount'=>$this->totalCount,'totalValue'=>$this->computeTotalValue()]);
    }
}

/**
 * Class CartLine
 * @property Product product
 * @property int quantity
 */
class CartLine extends BaseModel
{
    protected $_product;

    protected $_quantity;

//    public function __construct(Product $product, int $quantity)
//    {
//        $this->_product = $product;
//        $this->_quantity = $quantity;
//    }
}
