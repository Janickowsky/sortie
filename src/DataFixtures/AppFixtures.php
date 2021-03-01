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

        $sites = $this->createSite();
        foreach($sites as $site){
            $manager->persist($site);
        }

        $etats = $this->createEtat();
        foreach($etats as $etat){
            $manager->persist($etat);
        }

        $villes = $this->createVille();
        foreach($villes as $ville){
            $manager->persist($ville);
        }


        $lieux = $this->createLieu(15,$villes);
        foreach($lieux as $lieu){
            $manager->persist($lieu);
        }

        $users = $this->createUser(30,$sites);
        foreach($users as $user){
            $manager->persist($user);
        }

        $sorties = $this->createSortie(200,$lieux,$sites,$users,$etats);
        foreach($sorties as $sortie){
            $manager->persist($sortie);
        }

        $manager->flush();

    }

    /**
     * @return array
     * Fonction qui créer des campus
     */
    private function createSite(){
        $sites = Array();

        $site1 = new Site();
        $site1->setNom("SAINT HERBLAIN");

        $site2 = new Site();
        $site2->setNom("CHARTRES DE BRETAGNE");

        $site3 = new Site();
        $site3->setNom("LA ROCHE SUR YON");

        $sites[0] = $site1;
        $sites[1] = $site2;
        $sites[2] = $site3;
        return $sites;
    }

    /**
     * @return array
     * Fonciton qui créer les états des sorties
     */
    private function createEtat(){
        $etats = Array();

        $etat1 = new Etat();
        $etat1->setLibelle('Créée');

        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');

        $etat3 = new Etat();
        $etat3->setLibelle('Activité en cours');

        $etat4 = new Etat();
        $etat4->setLibelle('Passée');

        $etat5 = new Etat();
        $etat5->setLibelle('Annulée');

        $etat6 = new Etat();
        $etat6->setLibelle('Clôturée');

        $etats[0] = $etat1;
        $etats[1] = $etat2;
        $etats[2] = $etat3;
        $etats[3] = $etat4;
        $etats[4] = $etat5;
        $etats[5] = $etat6;
        return $etats;
    }

    /**
     * @return array
     * Fonction qui créer les villes
     */
    private function createVille(){
        $villes = Array();

        $ville1 = new Ville();
        $ville1->setNom("Nantes");
        $ville1->setCodePostal("44000");


        $ville2 = new Ville();
        $ville2->setNom("Angers");
        $ville2->setCodePostal("49000");


        $ville3 = new Ville();
        $ville3->setNom("ST Herblain");
        $ville3->setCodePostal("44800");


        $ville4 = new Ville();
        $ville4->setNom("Rennes");
        $ville4->setCodePostal("35000");


        $ville5 = new Ville();
        $ville5->setNom("Rezé");
        $ville5->setCodePostal("44400");

        $villes[0] = $ville1;
        $villes[1] = $ville2;
        $villes[2] = $ville3;
        $villes[3] = $ville4;
        $villes[4] = $ville5;

        return $villes;
    }

    /**
     * @param int $nbLieu
     * @param $villes
     * @return array
     * Fonction qui créer les lieux
     */
    private function createLieu(int $nbLieu, $villes){
        $generator = \Faker\Factory::create('fr_FR');
        $comp = new \Faker\Provider\fr_FR\Company($generator);
        $adresse = new \Faker\Provider\en_US\Address($generator);
        $number = new \Faker\Provider\Base($generator);

        $lieux = Array();

        for($i = 0; $i <= $nbLieu; $i++){
            $lieu = new Lieu();
            $lieu->setNom($comp->company());
            $lieu->setRue($adresse->buildingNumber() . ' ' . $adresse->streetName());
            $lieu->setLatitude($adresse->latitude($min=-90, $max=90));
            $lieu->setLongitude($adresse->longitude($min=-180, $max=180));
            $lieu->setVille($villes[$number->numberBetween(0,sizeof($villes) -1)]);


            $lieux[$i] = $lieu;
        }

        return $lieux;
    }

    /**
     * @param int $nbSortie
     * @param $lieux
     * @param $sites
     * @param $users
     * @param $etats
     * @return array
     * Fonction qui créer les sorties
     */
    private function createSortie(int $nbSortie, $lieux, $sites, $users, $etats){
        $generator = \Faker\Factory::create('fr_FR');
        $number = new \Faker\Provider\Base($generator);
        $lorem = new \Faker\Provider\Lorem($generator);
        $datt = new \Faker\Provider\DateTime($generator);

        $sorties = Array();

        for($i = 0; $i <= $nbSortie; $i++){
            $sortie= new Sortie();

            $sortie->setNom($lorem->word());
            $sortie->setDateHeureDebut($datt->dateTimeThisYear($max = '2021-12-31 23:59:59', $timezone = 'Europe/Paris'));
            $sortie->setDateLimiteInscription($datt->dateTimeThisYear($max = $sortie->getDateHeureDebut(), $timezone = 'Europe/Paris'));
            $sortie->setDuree($number->numberBetween($min=3, $max=24)*10);
            if($sortie->getDateHeureDebut() >= new \DateTime('now') && $sortie->getDateHeureDebut()->modify('+'. $sortie->getDuree().'minutes') <= new \DateTime('now') ){
                $sortie->setEtat($etats[2]);
            } else if($sortie->getDateHeureDebut() > new \DateTime('now')){
                $sortie->setEtat($etats[$number->numberBetween(0, 1)]);
            } else {
                $sortie->setEtat($etats[$number->numberBetween(3, 5)]);
            }

            $sortie->setLieu($lieux[$number->numberBetween(0,sizeof($lieux) -1 )]);
            $sortie->setInfosSortie($lorem->sentence());
            $sortie->setNbInscriptionMax($number->numberBetween($min=1, $max=8));
            $sortie->setCampus($sites[$number->numberBetween(0,sizeof($sites) -1)]);
            $sortie->setOrganisateur($users[$number->numberBetween(0,sizeof($users) -1 )]);

            $nbParticipant = $number->numberBetween(1,$sortie->getNbInscriptionMax());

            for($j=1; $j <= $nbParticipant; $j++){
                $user_id= $number->numberBetween(0,sizeof($users) -1);
                $sortie->addParticipant($users[$user_id]);
                $users[$user_id]->addSorty($sortie);
            }

            $sorties[$i] = $sortie;
        }
        return $sorties;
    }

    /**
     * @param int $nbUser
     * @param $sites
     * @return array
     * Fonction qui creer des utilisateurs
     */
    private function createUser(int $nbUser, $sites){
        $generator = \Faker\Factory::create('fr_FR');
        $internet = new \Faker\Provider\Internet($generator);
        $populator = new \Faker\Provider\fr_FR\Person($generator);
        $number = new \Faker\Provider\Base($generator);

        $users = Array();

        for($i = 0 ; $i <= $nbUser; $i++){
            $user = new User();
            $user->setPseudo($internet->userName());
            $user->setNom($populator->lastName());
            $user->setPrenom($populator->firstNameMale());
            $user->setEmail($internet->userName() . "@" . $internet->freeEmailDomain());
            $nb = $number->randomNumber(8);
            $user->setTelephone('06' . $nb);
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $user->setPlainPassword('test');
            $user->setCampus($sites[$number->numberBetween(0,sizeof($sites) -1 )]);

            $user->setPassword($user->encodePassword($this->encoder));

            $users[$i] = $user;
        }

       return $users;
    }
}
