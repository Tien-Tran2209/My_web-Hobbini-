<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment/success', name: 'payment_success')]
    public function success(PaymentService $paymentService): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        try {

            $paymentService->handleStripeSuccess($user);

            $this->addFlash('success', 'Paiement réussi !');
            return $this->redirectToRoute('user_profile');

        } catch (\Exception $e) {

            return $this->redirectToRoute('user_profile');
        }
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        $this->addFlash('error', 'Paiement annulé.');
        return $this->redirectToRoute('user_profile');
    }
}