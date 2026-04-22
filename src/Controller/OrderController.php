<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function checkout(EntityManagerInterface $manager, OrderRepository $orderRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $user->getCart();
        if (!$cart || $cart->getCartItems()->isEmpty()) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('product_index');
        }

        //Check stock before order 
        foreach ($cart->getCartItems() as $cartItem) {
            if ($cartItem->getQuantity() > $cartItem->getProduct()->getRemaining()) {
                $this->addFlash(
                    'error',
                    'Stock insuffisant pour ' . $cartItem->getProduct()->getName()
                );
                return $this->redirectToRoute('user_profile');
            }
        }

        $order = new Order();
        $order->setUser($user);
        $order->setStatus('En attente');
        $order->setCreatedAt(new \DateTime());

        //Set user order number (1,2,3,..) for each user
        $lastNumber = $orderRepo->getLastUserOrderNumber($user);
        $order->setUserOrderNumber($lastNumber + 1);

        $totalPrice = 0;

        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setPrice($cartItem->getProduct()->getPrice());

            $totalPrice += $cartItem->getProduct()->getPrice() * $cartItem->getQuantity();

            $manager->persist($orderItem);
        }

        $order->setTotalPrice($totalPrice);

        $manager->persist($order);

        foreach ($cart->getCartItems() as $item) {
            $manager->remove($item);
        }

        $manager->flush();

        $this->addFlash('success', 'Commande passée avec succès !');

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/order/{id}/cancel', name: 'order_cancel')]
    public function cancel(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($order->getStatus() !== 'En attente') {
            $this->addFlash('error', 'Impossible d’annuler cette commande.');
            return $this->redirectToRoute('user_profile');
        }

        $order->setStatus('Annulé');
        $order->setCancelledBy('client');

        $em->flush();

        $this->addFlash('success', 'Commande annulée avec succès.');

        return $this->redirectToRoute('user_profile');
    }
}