<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Product $product, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        //Check stock before ADD
        if ($product->getRemaining() <= 0) {
            $this->addFlash('error', 'Produit en rupture de stock');
            return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
        }

        $cart = $user->getCart() ?? new Cart();
        $cart->setUser($user);

        $existingItem = null;
        foreach ($cart->getCartItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {
            //Check limit stock
            if ($existingItem->getQuantity() >= $product->getRemaining()) {
                $this->addFlash('error', 'Stock insuffisant');
                return $this->redirectToRoute('user_profile');
            }

            $existingItem->setQuantity($existingItem->getQuantity() + 1);
            $manager->persist($existingItem);

        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);

            $manager->persist($cartItem);
        }

        $manager->persist($cart);
        $manager->flush();

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(CartItem $item, EntityManagerInterface $manager): Response
    {
        $manager->remove($item);
        $manager->flush();

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cart/increase/{id}', name: 'cart_increase')]
    public function increase(CartItem $item, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($item->getCart()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $product = $item->getProduct();

        // Check stock
        if ($item->getQuantity() >= $product->getRemaining()) {
            $this->addFlash('error', 'Vous ne pouvez pas ajouter plus de ce produit (stock insuffisant).');
            return $this->redirectToRoute('user_profile');
        }

        $item->setQuantity($item->getQuantity() + 1);
        $em->flush();

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease(CartItem $item, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($item->getCart()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $newQty = $item->getQuantity() - 1;

        if ($newQty <= 0) {
            $em->remove($item);
        } else {
            $item->setQuantity($newQty);
        }

        $em->flush();

        return $this->redirectToRoute('user_profile');
    }
}