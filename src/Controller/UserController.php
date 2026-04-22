<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function profile(CartRepository $cartRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepo->findOneBy(['user' => $user]);

        $orders = $user->getOrders(); 

        return $this->render('user/profile.html.twig', [
            'cart' => $cart,
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}

