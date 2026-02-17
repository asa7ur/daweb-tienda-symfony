<?php

namespace App\Controller\Admin;

use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

#[IsGranted('ROLE_ADMIN')]
class ProductoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Producto::class;
    }
    
    #[\Override]
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('codigo')
            ->add('nombre')
            ->add('categoria');
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('codigo', 'Código'),
            TextField::new('nombre', 'Nombre Completo'),
            TextField::new('nombre_corto', 'Nombre Corto'),
            NumberField::new('precio')->setNumDecimals(2),
            AssociationField::new('categoria', 'Categoría')->setRequired(true),
            TextEditorField::new('descripcion', 'Descripción del Producto')->hideOnIndex(),
        ];
    }
}
