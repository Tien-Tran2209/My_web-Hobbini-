<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

class AdminApiController extends AbstractController
{
    #[Route('/api/admin/test', name: 'api_admin_test', methods: ['GET'])]

    #[IsGranted('ROLE_ADMIN')]

    #[OA\Get(
        path: '/api/admin/test',
        summary: 'Test ROLE_ADMIN access',
        security: [['Bearer' => []]],
        tags: ['Admin API']
    )]

    #[OA\Response(
        response: 200,
        description: 'ROLE_ADMIN access granted'
    )]

    public function adminTest(): JsonResponse
    {
        return $this->json([
            'message' => 'ROLE_ADMIN access granted'
        ]);
    }
}