<?php

namespace App\Controller\Admin;

use App\Entity\CollectionDeVoiture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class CollectionDeVoitureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CollectionDeVoiture::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // For whatever reason show isn't in the menu, bu default
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ,
            TextField::new('description'),
            AssociationField::new('Voitures')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/CollectionDeVoiture_Voiture.html.twig')
                ,
            AssociationField::new('membre'),
        ];
    }

}
