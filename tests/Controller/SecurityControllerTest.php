<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageIsAccessible(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h3', 'Bienvenue');
    }

    public function testAnonymousUserCannotAccessAdminDashboard(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/dashboard');

        $this->assertResponseRedirects('/login');
    }

    public function testAdminUserCanAccessDashboard(): void
    {
        $client = static::createClient();

        $entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();

        $user = new User();
        $user->setEmail(uniqid() . '@test.com');
        $user->setPassword('password');
        $user->setRoles(['ROLE_ADMIN']);

        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);

        $client->request('GET', '/admin/dashboard');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageContainsFormFields(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertCount(
            1,
            $crawler->filter('input[name="email"]')
        );

        $this->assertCount(
            1,
            $crawler->filter('input[name="password"]')
        );
    }
}