<?php

namespace App\Form;

use App\Entity\Galerie;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\VoitureRepository;
use Doctrine\ORM\QueryBuilder;

class GalerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dump($options);
        $galerie = $options['data'] ?? null;
        $member = $galerie->getCreator();

        $builder
            ->add('description')
            ->add('published')
            ->add('creator', null, [
                'disabled'   => true,
            ])
            ->add('Voiture', null, [
                'query_builder' => function (VoitureRepository $er) use ($member) {
                        return $er->createQueryBuilder('g')
                        ->leftJoin('g.galeries', 'i')
                        ->andWhere('i.creator = :member')
                        ->setParameter('member', $member)
                        ;
                    }
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Galerie::class,
        ]);
    }
}
