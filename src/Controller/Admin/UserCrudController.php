<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    private $encoder;
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['addPass']
        ];
    }

    //Avec evenement
    public function addPass(BeforeEntityPersistedEvent $event){
        $user = $event->getEntityInstance();
        $user->setPlainPassword('test');
        $user->setPassword($user->encodePassword($this->encoder));
    }

    //avec sucharge de methode
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance->getActif() == false && count($entityInstance->getSorties()) == 0 && count($entityInstance->getMesEvenements()) == 0){
            $this->addFlash('success','Utilisateur supprimer avec succès');
            parent::deleteEntity($entityManager, $entityInstance);
        }else{
            $this->addFlash('error',"Impossible de supprimer l'utilisateur");
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('email'),
            TextField::new('telephone'),
            BooleanField::new('actif'),
            AssociationField::new('campus','Site')
            ->setFormTypeOption('choice_label','nom')
            ->formatValue(function($value,$entity){
                return $entity->getCampus() ? $entity->getCampus()->getNom(): 'Pas de campus';
            }),
        ];
    }

}
