<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

#[IsGranted('ROLE_ADMIN')]
class UsuarioCrudController extends AbstractCrudController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    /**
     * Inicializamos el PasswordHasher
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }
    
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('login')
            ->add('email')
        ;
    }
    
    /*
    #[\Override]
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('dni')
            ->add('email');
    }
     * 
     */
    
    /**
     * Métodos que posibilita el poder ecriptar las password del usuario al crear uno o actualizarlo en caso de que exista.
     * @param EntityManagerInterface $entityManager
     * @param $entity
     * @return void
     */

    #[\Override]
    public function updateEntity(EntityManagerInterface $entityManager, $entity): void
    {
        $event = new BeforeEntityPersistedEvent($entity);
        $this->passwordHash($event);
        parent::updateEntity($entityManager, $entity);
    }

    #[\Override]
    public function persistEntity(EntityManagerInterface $entityManager, $entity): void
    {
        $event = new BeforeEntityPersistedEvent($entity);
        $this->passwordHash($event);
        parent::persistEntity($entityManager, $entity);
    }
    
    /**
     * Función que posibilita el poder encryptar las password de los usuarios.
     * @param BeforeEntityPersistedEvent $event
     * @return void
     */
    public function passwordHash(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if(!$entity instanceof Usuario){
            return;
        }
        $entity->setPassword($this->userPasswordHasher->hashPassword($entity,$entity->getPlainPassword()));
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
