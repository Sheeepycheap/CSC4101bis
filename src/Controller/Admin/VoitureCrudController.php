<?php

namespace App\Controller\Admin;

use App\Entity\Voiture;
use App\Entity\Moteur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VoitureCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Voiture::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('description'),
            AssociationField::new('collectionDeVoiture')
            ,
            TextField::new('marque'),
            TextField::new('modele'),
            TextField::new('couleur'),
            AssociationField::new('Moteur') // remplacer par le nom de l'attribut spécifique, par exemple 'bodyShape'
            ->onlyOnDetail()
            ->formatValue(function ($value, $entity) {
                return implode(', ', $entity->getMoteur()->toArray()); // ici getBodyShapes()
            })
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
