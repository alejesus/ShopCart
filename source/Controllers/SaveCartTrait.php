<?php
namespace Source\Controllers;

use Source\Models\Cart;
use Source\Models\CartItems;
use Exception;

/**
 * Trait responsável pelo cadastro do carrinho de compras na base de dados.
 * Trait responsible for shopping cart registration in the database.
 */
trait SaveCartTrait
{

    /**
     * Salva o carrinho na base de dados.
     * Saves the cart to the database.
     * @param int $userId
     * @param array $dataCart
     * @param array|null $dataShipment
     * @return int
     */
    private function saveCartDB(int $userId, array $dataCart, array $dataShipment = null): ?int
    {
        $cart             = new Cart();
        $cart->user_id    = $userId;
        $cart->subtotal   = $dataCart['subtotal'];
        $cart->discount   = ($dataCart['discount'] ?? null);
        $cart->total      = $dataCart['total'];
        $cart->checkedout = 0;

        if ($dataShipment) {
            $cart->shipment_type     = $dataShipment['type'];
            $cart->shipment_value    = $dataShipment['value'];
            $cart->shipment_deadline = $dataShipment['deadline'];
        }

        $idCart = $cart->save();

        if ($cart->fail()) {
            throw new Exception($this->ajaxMessage($cart->fail()->getMessage(), 'error'));
        }

        $this->saveCartItemDB($idCart, $dataCart);

        return $idCart;
    }

    /**
     * Salva o item do carrinho na base de dados.
     * Saves the cart item to the database.
     * @param int $cartId
     * @param array $dataCart
     * @return void
     */
    private function saveCartItemDB(int $cartId, array $dataCart) : void
    {
        foreach ($dataCart['items'] as $item) {

            $itemCart             = new CartItems();
            $itemCart->cart_id    = $cartId;
            $itemCart->product_id = $item['id'];
            $itemCart->price      = $item['price'];
            $itemCart->quantity   = $item['amount'];
            $itemCart->subtotal   = $item['subtotal'];
            $itemCart->discount   = $item['discount'];
            $itemCart->total      = $item['total'];
            $itemCart->save();

            if ($itemCart->fail()) {
                throw new Exception($this->ajaxMessage($itemCart->fail()->getMessage(), 'error'));
            }

        }

    }

}
