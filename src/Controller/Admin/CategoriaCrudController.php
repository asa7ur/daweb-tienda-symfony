<?php

namespace App\Controller\Admin;

use App\Entity\Categoria;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

#[IsGranted('ROLE_ADMIN')]
class CategoriaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categoria::class;
    }
    
    #[\Override]
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('codigo')
            ->add('nombre')
        ;
    }
    
    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('codigo', 'Código de Categoría'),
            TextField::new('nombre', 'Nombre de la Categoría'),
            AssociationField::new('productos')->hideOnForm()
        ];
    }
}
