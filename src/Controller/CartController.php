<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(
        Product $product,
        CartService $cartService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $cartService->addProduct(
                $this->getUser(),
                $product
            );

            $this->addFlash(
                'success',
                'Produit ajouté au panier.'
            );

        } catch (\Exception $e) {

            $this->addFlash(
                'error',
                $e->getMessage()
            );
        }

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(
        CartItem $item,
        CartService $cartService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $cartService->removeItem(
                $item,
                $this->getUser()
            );

        } catch (\Exception $e) {

            $this->addFlash(
                'error',
                $e->getMessage()
            );
        }

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cart/increase/{id}', name: 'cart_increase')]
    public function increase(
        CartItem $item,
        CartService $cartService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $cartService->increaseQuantity(
                $item,
                $this->getUser()
            );

        } catch (\Exception $e) {

            $this->addFlash(
                'error',
                $e->getMessage()
            );
        }

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease(
        CartItem $item,
        CartService $cartService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            $cartService->decreaseQuantity(
                $item,
                $this->getUser()
            );

        } catch (\Exception $e) {

            $this->addFlash(
                'error',
                $e->getMessage()
            );
        }

        return $this->redirectToRoute('user_profile');
    }
}