<?php

namespace App\Controller\Admin;

use App\Entity\Moteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class MoteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Moteur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //yield IdField::new('id'),
            yield TextField::new('label'),
            yield TextField::new('description'),
            yield AssociationField::new('parent')
            ->onlyOnDetail(),
            yield AssociationField::new('sousMoteur')
            ->onlyOnDetail(),
            yield AssociationField::new('voitures')
            ->onlyOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // For whatever reason show isn't in the menu, bu default
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
}
