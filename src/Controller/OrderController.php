<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\OrderCheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/checkout/{method}', name: 'checkout')]
    public function checkout(string $method, OrderCheckoutService $checkoutService): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        try {

            $url = $checkoutService->checkout($user, $method);

            if ($method === 'cod') {
                $this->addFlash('success', 'Commande passée avec succès !');
                return $this->redirectToRoute('user_profile');
            }

            if ($method === 'stripe' && $url) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('user_profile');

        } catch (\Exception $e) {

            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('user_profile');
        }
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