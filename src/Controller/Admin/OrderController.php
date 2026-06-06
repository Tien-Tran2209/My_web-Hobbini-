<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\OrderManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'admin_order_index')]
    public function index(
        OrderRepository $orderRepo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $query = $orderRepo->createQueryBuilder('o')
            ->orderBy('o.created_at', 'DESC');

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

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
    public function updateStatus(
        Order $order,
        Request $request,
        OrderManagerService $orderService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        try {
            $orderService->updateStatus($order, $newStatus);
            $message = match ($newStatus) {
                'En attente' => 'Commande remise en attente.',
                'Validé' => 'Commande validée avec succès.',
                'Expédié' => 'Commande marquée comme expédiée.',
                'Annulé' => 'Commande annulée.',
                default => 'Statut mis à jour.'
            };

            $this->addFlash('success', $message);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_order_index');
    }
}