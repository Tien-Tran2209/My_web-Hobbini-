<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'admin_order_index')]
    public function index(OrderRepository $orderRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $orders = $orderRepo->findBy([], ['created_at' => 'DESC']);

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/{id}', name: 'admin_order_show')]
    public function show(int $id, OrderRepository $orderRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $order = $orderRepo->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/update-status', name: 'admin_order_update_status', methods: ['POST'])]
    public function updateStatus(Order $order, Request $request, EntityManagerInterface $em): Response
    {
       $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $oldStatus = trim($order->getStatus());
        $newStatus = trim($request->request->get('status'));

        $allowedStatuses = [
            'En attente',
            'Validé',
            'Expédié',
            'Annulé'
        ];

        if (!$newStatus || !in_array($newStatus, $allowedStatuses)) {
            return $this->redirectToRoute('admin_order_index');
        }

        if ($oldStatus !== 'Expédié' && $newStatus === 'Expédié') {

            foreach ($order->getOrderItems() as $item) {

                $product = $item->getProduct();

                $remaining = $product->getRemaining();

                $qty = $item->getQuantity();

                if ($remaining < $qty) {
                    $this->addFlash(
                        'error',
                        'Stock insuffisant pour ' . $product->getName()
                    );

                    return $this->redirectToRoute('admin_order_index');
                }

                $product->setSold(
                    $product->getSold() + $qty
                );
            }
        }

        if ($oldStatus === 'Expédié' && $newStatus !== 'Expédié') {

            foreach ($order->getOrderItems() as $item) {

                $product = $item->getProduct();

                $qty = $item->getQuantity();

                $newSold = $product->getSold() - $qty;

                if ($newSold < 0) {
                    $newSold = 0;
                }

                $product->setSold($newSold);
            }
        }

        if ($newStatus === 'Annulé') {
            $order->setCancelledBy('admin');
        }

        $order->setStatus($newStatus);

        $em->flush();

        return $this->redirectToRoute('admin_order_index');
    }
}

