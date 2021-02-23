<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        require_once 'vendor/autoload.php';
        $generator = \Faker\Factory::create('fr_FR');
        $populator = new \Faker\Provider\fr_FR\Person($generator);
        $number = new \Faker\Provider\Base($generator);

        $site1 = new Site();
        $site1->setNom("SAINT HERBLAIN");
        $manager->persist($site1);

        $site2 = new Site();
        $site2->setNom("CHARTRES DE BRETAGNE");
        $manager->persist($site2);

        $site3 = new Site();
        $site3->setNom("LA ROCHE SUR YON");
        $manager->persist($site3);

        $lorem = new \Faker\Provider\Lorem($generator);

        $datt = new \Faker\Provider\DateTime($generator);

        $user1 = new User();
        for($i = 0 ; $i < 10; $i++){
            $user = new User();
            $user->setNom($populator->lastName());
            $user->setPrenom($populator->firstNameMale());
            $user->setEmail($user->getPrenom() . '.' . $user->getNom() . '@campus-eni.fr');

            $nb = $number->randomNumber(8);

            $user->setTelephone('06' . $nb);
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $user->setPlainPassword('test');
            $user->setCampus($site1);

            $user->setPassword($this->encoder->encodePassword($user,$user->getPlainPassword()));
            $user1 = $user;
            $manager->persist($user);
        }

        $etat1 = new Etat();
        $etat1->setLibelle('Créée');
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Clôturée');
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('Activité en cours');
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Passée');
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $manager->persist($etat6);


        $ville1 = new Ville();
        $ville1->setNom("Nantes");
        $ville1->setCodePostal("44000");
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom("Angers");
        $ville2->setCodePostal("49000");
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom("ST Herblain");
        $ville3->setCodePostal("44800");
        $manager->persist($ville3);

        $ville4 = new Ville();
        $ville4->setNom("Rennes");
        $ville4->setCodePostal("35000");
        $manager->persist($ville4);

        $ville5 = new Ville();
        $ville5->setNom("Rezé");
        $ville5->setCodePostal("44400");
        $manager->persist($ville5);


        $comp = new \Faker\Provider\fr_FR\Company($generator);
        $adresse = new \Faker\Provider\en_US\Address($generator);

        $lieu1 = new Lieu();
        for($i = 0; $i < 10; $i++){
            $lieu = new Lieu();
            $lieu->setNom($comp->company());
            $lieu->setRue($adresse->buildingNumber() . ' ' . $adresse->streetName());
            $lieu->setLatitude($adresse->latitude($min=-90, $max=90));
            $lieu->setLongitude($adresse->longitude($min=-180, $max=180));
            $lieu->setVille($ville3);
            $manager->persist($lieu);

            $lieu1 = $lieu;
        }





        for($i = 0; $i < 30; $i++){
            $sortie= new Sortie();

            $sortie->setNom($lorem->word());
            $sortie->setDateHeureDebut($datt->dateTimeThisYear($max = '2021-12-31 23:59:59', $timezone = 'Europe/Paris'));
            $sortie->setDateLimiteInscription($datt->dateTimeThisYear($max = 'now', $timezone = 'Europe/Paris'));
            $sortie->setDuree(1);
            $sortie->setEtat($etat2);
            $sortie->setLieu($lieu1);
            $sortie->setInfosSortie($lorem->sentence());
            $sortie->setNbInscriptionMax($number->numberBetween($min=2, $max=20));
            $sortie->setCampus($site1);
            $sortie->setOrganisateur($user1);
            $manager->persist($sortie);
        }
        $manager->flush();

    }
}
