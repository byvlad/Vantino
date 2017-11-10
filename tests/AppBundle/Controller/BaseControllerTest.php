<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Vant;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUser()
    {
        $user = new User;
        $user->setEmail('test@test.ru');
        $user->setUsername('Testuser');
        $user->setEnabled(1);
        $user->setPlainPassword('testpassword');

        $this->em->persist($user);
        $this->em->flush();

        $this->assertEquals(12, $user->getId());
        $this->assertContains('Testuser', $user->getUsername());
    }

    public function testVant()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'Testuser']);

        $vant = new Vant;
        $vant->setUser($user);
        $vant->setType('public');
        $vant->setContent('#Test vant');

        $this->em->persist($vant);
        $this->em->flush();

        $this->assertEquals(201, $vant->getId());
        $this->assertContains('Test vant', $vant->getContent());
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search/hashtag/Test');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("#Test")')->count()
        );

    }
}