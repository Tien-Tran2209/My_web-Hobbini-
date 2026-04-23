<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OrderRepository;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function profile(CartRepository $cartRepo, 
    OrderRepository $orderRepo,  
    PaginatorInterface $paginator,
    Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepo->findOneBy(['user' => $user]);

        //$orders = $user->getOrders(); 
        $query = $orderRepo->createQueryBuilder('o')
        ->where('o.user = :user')
        ->setParameter('user', $user)
        ->orderBy('o.created_at', 'DESC')
        ->getQuery();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('user/profile.html.twig', [
            'cart' => $cart,
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}

