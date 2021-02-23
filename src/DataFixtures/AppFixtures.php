<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {
        $faker = Faker\Factory::create('fr_FR');

        $populator = new Faker\ORM\Doctrine\Populator($faker, $em);
        $populator->addEntity(User::class, 10);
        $em->persist($populator);
        $em->flush();
    }
}
