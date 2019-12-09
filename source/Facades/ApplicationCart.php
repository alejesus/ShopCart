<?php

namespace Source\Facades;

use Source\Models\Product;

/**
 * Classe responsável pela regra de negócio das interações com o carrinho de compras.
 * Class responsible for the business rule of shopping cart interactions.
 */
class ApplicationCart
{
    /**
     * Inicializa a classe verificando a existência de sessão
     * do sistema e do carrinho.
     * Initializes the class by checking for system and
     * cart session existence.
     **/
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['cart'] = (!empty($_SESSION['cart']) ? $_SESSION['cart'] : []);
    }

    /**
     * Retorna a sessão do carrinho.
     * Returns the cart session.
     * @return array|null
     */
    public function cart():  ? array
    {
        return $_SESSION['cart'];
    }

    /**
     * Adiciona um item para o carrinho de compras
     * Add an item to the shopping cart.
     * @param Product $product
     * @return ApplicationCart
     */
    public function add(Product $product) : ApplicationCart
    {
        $itemWithDiscount = (!empty($product->discount) ? $product->price - ($product->price * $product->discount) : $product->price);

        $_SESSION['cart']['amount']   = (!empty($_SESSION['cart']['amount']) ? $_SESSION['cart']['amount'] : 0);
        $_SESSION['cart']['amount'] += 1;

        $_SESSION['cart']['subtotal'] = ($_SESSION['cart']['subtotal'] ?? 0);
        $_SESSION['cart']['subtotal'] += $itemWithDiscount;

        $_SESSION['cart']['total'] = (!empty($_SESSION['cart']['discount']) ?
            $_SESSION['cart']['subtotal'] - ($_SESSION['cart']['subtotal'] * $_SESSION['cart']['discount']) :
            $_SESSION['cart']['subtotal']);

        if (empty($_SESSION['cart']['items'][$product->id])) {

            $_SESSION['cart']['items'][$product->id] = [

                "id"       => $product->id,
                "product"  => $product->title,
                "price"    => $product->price,
                "subtotal" => $product->price,
                "discount" => ($product->discount ?? null),
                "total"    => $itemWithDiscount,
                "amount"   => 1,
            ];

            return $this;
        }

        $_SESSION['cart']['items'][$product->id]['amount'] += 1;
        $_SESSION['cart']['items'][$product->id]['subtotal'] = $product->price * $_SESSION['cart']['items'][$product->id]['amount'];
        $_SESSION['cart']['items'][$product->id]['total'] += $itemWithDiscount;

        return $this;
    }

    /**
     * Remove um item para o carrinho de compras.
     * Remove an item to the shopping cart.
     * @param Product $product
     * @return ApplicationCart
     */
    public function remove(Product $product): ApplicationCart
    {
        if (!empty($_SESSION['cart']['items'][$product->id])) {

            $itemWithDiscount = (!empty($product->discount) ? $product->price - ($product->price * $product->discount) : $product->price);

            $_SESSION['cart']['amount'] -= 1;
            $_SESSION['cart']['subtotal'] -= $itemWithDiscount;
            $_SESSION['cart']['total'] = (!empty($_SESSION['cart']['discount']) ?
                $_SESSION['cart']['subtotal'] - ($_SESSION['cart']['subtotal'] * $_SESSION['cart']['discount']) :
                $_SESSION['cart']['subtotal']);

            if ($_SESSION['cart']['items'][$product->id]['amount'] > 1) {

                $_SESSION['cart']['items'][$product->id]['amount'] -= 1;
                $_SESSION['cart']['items'][$product->id]['subtotal'] = $product->price * $_SESSION['cart']['items'][$product->id]['amount'];
                $_SESSION['cart']['items'][$product->id]['total'] -= $itemWithDiscount;

                return $this;
            }

            unset($_SESSION['cart']['items'][$product->id]);
            return $this;
        }

        return $this;
    }

    /**
     * Limpa o carrinho de compras.
     * Clears the shopping cart.
     * @return ApplicationCart
     */
    public function clear(): ApplicationCart
    {
        $_SESSION['cart'] = [];

        return $this;
    }

}
