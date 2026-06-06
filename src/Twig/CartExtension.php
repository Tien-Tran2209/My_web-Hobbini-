<?php

namespace App\Twig;

use App\Repository\CartItemRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function __construct(
        private CartItemRepository $cartItemRepository,
        private Security $security
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_count', [$this, 'getCartCount']),
        ];
    }

    public function getCartCount(): int
    {
        $user = $this->security->getUser();

        if (!$user) {
            return 0;
        }

        $items = $this->cartItemRepository
            ->createQueryBuilder('ci')
            ->join('ci.cart', 'c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $count = 0;

        foreach ($items as $item) {
            $count += $item->getQuantity();
        }

        return $count;
    }
}