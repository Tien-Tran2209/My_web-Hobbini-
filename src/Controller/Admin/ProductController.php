<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/new', name: 'admin_product_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Le produit a été ajouté!');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // EDIT
    #[Route('/edit/{id}', name: 'admin_product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès!');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/order/{id}/cancel', name: 'order_cancel')]
    public function cancel(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        //Cancellation is only permitted if you are the requester.
        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        //Only cancel if you are currently in ATTENTION mode
        if ($order->getStatus() !== 'EN ATTENTE') {
            $this->addFlash('error', 'Impossible d’annuler cette commande.');
            return $this->redirectToRoute('user_profile');
        }

        $order->setStatus('ANNULÉ');
        $em->flush();

        $this->addFlash('success', 'Commande annulée avec succès.');

        return $this->redirectToRoute('user_profile');
    }
}