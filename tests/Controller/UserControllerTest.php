<?php


namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class UserControllerTest extends WebTestCase
{
    protected $client;
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testAddUser()
    {
        $data = array(
          'name' => 'testname',
          'email' => 'test@test.com'
        );

       $client = static::createClient();
       $client->request('POST', '/users', [], [], [], json_encode($data));

       $this->assertEquals(201, $client->getResponse()->getStatusCode());

    }

    public function testGetAllUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetSingleUserNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/users/1');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetSingleUserFound()
    {
        $client = static::createClient();
        $client->request('GET', '/users/72');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}