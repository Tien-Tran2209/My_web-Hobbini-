<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

class UserApiController extends AbstractController
{
    #[Route('/api/test', name: 'api_test', methods: ['GET'])]

    #[IsGranted('ROLE_USER')]

    #[OA\Get(
        path: '/api/test',
        summary: 'Test ROLE_USER access',
        security: [['Bearer' => []]],
        tags: ['User API']
    )]

    #[OA\Response(
        response: 200,
        description: 'ROLE_USER access granted'
    )]

    public function test(): JsonResponse
    {
        return $this->json([
            'message' => 'ROLE_USER access granted'
        ]);
    }
}