<?php


namespace App\Tests\Domain\Security;

use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class LoginAuthenticatorTest extends WebTestCase
{
    /** @var LoaderInterface $loader */
    protected $loader;

    /** @var EntityManagerInterface $entityManager */
    protected $entityManager;

    /** @var KernelBrowser $client */
    protected $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->entityManager = self::$container->get('doctrine')->getManager();
        $this->loader = self::$container->get('fidry_alice_data_fixtures.loader.doctrine');
    }

    public function tearDown(): void
    {
        $purger = new DoctrineOrmPurger($this->entityManager);
        $purger->purge();

        //  ensure the kernel is shut down before calling the method
        parent::tearDown();
    }

    /**
     * Load fixture
     */
    private function loadFixtures():void
    {
        $this->loader->load([
            __DIR__ . '/../../Fixtures/User/loginUser.yml'
        ]);
    }

    public function testLogin()
    {
        $this->loadFixtures();

        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode([
                'username' => 'alice',
                'password' => '123456',
            ])
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('content-type', 'application/json'));

        $output = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(3, $output);
        $this->assertEquals('alice@contact.fr', $output['user']['email']);
        $this->assertEquals('ROLE_USER', $output['user']['roles'][0]);
    }

    public function testLoginWithInvalidUsername()
    {
        $this->loadFixtures();

        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode([
                'username' => 'john',
                'password' => '123456',
            ])
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('content-type', 'application/json'));
    }

    public function testLoginWithNoCredentials()
    {
        $this->loadFixtures();

        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [],
            json_encode([])
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }
}