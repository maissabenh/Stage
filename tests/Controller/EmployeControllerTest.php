<?php

namespace App\Test\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/employe/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Employe::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Employe index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'employe[matricule]' => 'Testing',
            'employe[motdepasse]' => 'Testing',
            'employe[nom]' => 'Testing',
            'employe[prenom]' => 'Testing',
            'employe[mail]' => 'Testing',
            'employe[telephone]' => 'Testing',
            'employe[adresse]' => 'Testing',
            'employe[position]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employe();
        $fixture->setMatricule('My Title');
        $fixture->setMotdepasse('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setMail('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setPosition('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Employe');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employe();
        $fixture->setMatricule('Value');
        $fixture->setMotdepasse('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setMail('Value');
        $fixture->setTelephone('Value');
        $fixture->setAdresse('Value');
        $fixture->setPosition('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'employe[matricule]' => 'Something New',
            'employe[motdepasse]' => 'Something New',
            'employe[nom]' => 'Something New',
            'employe[prenom]' => 'Something New',
            'employe[mail]' => 'Something New',
            'employe[telephone]' => 'Something New',
            'employe[adresse]' => 'Something New',
            'employe[position]' => 'Something New',
        ]);

        self::assertResponseRedirects('/employe/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMatricule());
        self::assertSame('Something New', $fixture[0]->getMotdepasse());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getMail());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getPosition());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Employe();
        $fixture->setMatricule('Value');
        $fixture->setMotdepasse('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setMail('Value');
        $fixture->setTelephone('Value');
        $fixture->setAdresse('Value');
        $fixture->setPosition('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/employe/');
        self::assertSame(0, $this->repository->count([]));
    }
}
