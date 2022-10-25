<?php

use App\Entity\Galerie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class GalerieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
    return Galerie::class;
    }

    public function configureFields(string $pageName): iterable
    {

    return [
        IdField::new('id')->hideOnForm(),
        AssociationField::new('creator'),
        BooleanField::new('published')
        ->onlyOnForms()
        ->hideWhenCreating(),
        TextField::new('description'),
        AssociationField::new('Voiture')
        ->onlyOnForms()
        // on ne souhaite pas gérer l'association entre les
        // [objets] et la Galerie dès la crétion de la
        // Galerie
        ->hideWhenCreating()
        ->setTemplatePath('admin/fields/CollectionDeVoiture_Voiture.html.twig')
        // Ajout possible seulement pour des [objets] qui
        // appartiennent même propriétaire de l'[inventaire]
        // que le creator de la Galerie
        ->setQueryBuilder(
            function (QueryBuilder $queryBuilder) {
            // récupération de l'instance courante de Galerie
            $currentGalerie = $this->getContext()->getEntity()->getInstance();
            $creator = $currentGalerie->getcreator();
            $memberId = $creator->getId();
            // charge les seuls [objets] dont le 'owner' de l'[inventaire] est le creator de la galerie
            $queryBuilder->leftJoin('entity.CollectionDeVoiture', 'i')
                ->leftJoin('i.owner', 'm')
                ->andWhere('m.id = :member_id')
                ->setParameter('member_id', $memberId);    
            return $queryBuilder;
            }),   
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