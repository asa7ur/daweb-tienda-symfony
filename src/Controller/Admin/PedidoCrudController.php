<?php

namespace App\Controller\Admin;

use App\Entity\Pedido;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

#[IsGranted('ROLE_ADMIN')]
class PedidoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pedido::class;
    }
    
    #[\Override]
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('fecha')
            ->add('coste')
        ;
    }

    
    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('fecha', 'Fecha del Pedido'),
            TextField::new('code', 'Código'),
            NumberField::new('coste', 'Coste Total')
                ->setNumDecimals(2),
            AssociationField::new('usuario', 'Cliente'),
        ];
    }
    
}
