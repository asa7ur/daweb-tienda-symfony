<?php

namespace App\Controller\Admin;

use App\Entity\PedidoProducto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class PedidoProductoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PedidoProducto::class;
    }
    
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('pedido')
            ->add('producto')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            // AssociationField se usa para las relaciones ManyToOne definidas en la entidad
            AssociationField::new('pedido', 'ID del Pedido'),
            AssociationField::new('producto', 'Producto Seleccionado'),
            IntegerField::new('unidades', 'Unidades'),
        ];
    }
}
