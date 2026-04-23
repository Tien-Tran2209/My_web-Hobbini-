<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function addProduct(
        $user,
        Product $product
    ): void {
        if ($product->getRemaining() <= 0) {
            throw new \Exception(
                'Produit en rupture de stock.'
            );
        }

        $cart = $user->getCart();

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);

            $this->em->persist($cart);
        }

        $existingItem = null;

        foreach ($cart->getCartItems() as $item) {
            if (
                $item->getProduct()->getId()
                === $product->getId()
            ) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {

            if (
                $existingItem->getQuantity()
                >= $product->getRemaining()
            ) {
                throw new \Exception(
                    'Stock insuffisant.'
                );
            }

            $existingItem->setQuantity(
                $existingItem->getQuantity() + 1
            );

        } else {

            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

            $this->em->persist($cartItem);
        }

        $this->em->flush();
    }

    public function removeItem(
        CartItem $item,
        $user
    ): void {
        $this->checkOwner($item, $user);

        $this->em->remove($item);
        $this->em->flush();
    }

    public function increaseQuantity(
        CartItem $item,
        $user
    ): void {
        $this->checkOwner($item, $user);

        $product = $item->getProduct();

        if (
            $item->getQuantity()
            >= $product->getRemaining()
        ) {
            throw new \Exception(
                'Stock insuffisant.'
            );
        }

        $item->setQuantity(
            $item->getQuantity() + 1
        );

        $this->em->flush();
    }

    public function decreaseQuantity(
        CartItem $item,
        $user
    ): void {
        $this->checkOwner($item, $user);

        $newQty = $item->getQuantity() - 1;

        if ($newQty <= 0) {
            $this->em->remove($item);
        } else {
            $item->setQuantity($newQty);
        }

        $this->em->flush();
    }

    private function checkOwner(
        CartItem $item,
        $user
    ): void {
        if ($item->getCart()->getUser() !== $user) {
            throw new \Exception(
                'Accès refusé.'
            );
        }
    }
}