<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('email');
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('login', 'Nombre de usuario');
        yield TextField::new('email', 'Correo electrónico');
        yield IntegerField::new('number', 'Número');
        yield ArrayField::new('roles', 'Roles');

        // Campo virtual para la contraseña
        yield TextField::new('plainPassword', 'Contraseña')
            ->setFormType(PasswordType::class) // Esto hace que se vea con asteriscos (*)
            ->onlyOnForms() // Solo aparece al crear o editar
            // La contraseña es obligatoria al crear, pero opcional al editar
            ->setRequired($pageName === Action::NEW)
            ->setHelp($pageName === Action::EDIT ? 'Deja en blanco para mantener la contraseña actual' : '');
    }

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
    public function passwordHash(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if (!$entity instanceof Usuario) {
            return;
        }

        // Solo hasheamos si hay un valor en plainPassword (nueva contraseña introducida)
        $plainPassword = $entity->getPlainPassword();
        if (!empty($plainPassword)) {
            $hashedPassword = $this->userPasswordHasher->hashPassword($entity, $plainPassword);
            $entity->setPassword($hashedPassword);
            $entity->eraseCredentials(); // Limpia la contraseña en claro por seguridad
        }
    }
}
