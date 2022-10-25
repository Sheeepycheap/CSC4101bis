<?php

namespace App\DataFixtures;

use App\Entity\CollectionDeVoiture;
use App\Entity\Galerie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Voiture; 
use App\Entity\Membre; 
use App\Entity\Moteur;
use phpDocumentor\Reflection\PseudoTypes\False_;

class AppFixtures extends Fixture
{

    /**
     * Generates initialization data for voitures : [description, marque,modele,couleur]
     * @return \\Generator
     */
    private static function VoitureDataGenerator()
    {
        yield ["Voiture de Andrew Tate","Bugatti","V3","bleu"];
        yield ["Autre Voiture de Andrew Tate","Bugatti","V4","rouge"];
        yield ["véhicule d'un alien","UFO","Inconnue","gris"];
        
    }

    /**
     * Generates initialization data for voitures : [id, description]
     * @return \\Generator
     */
    private static function CollectionDataGenerator()
    {
        yield [1," Collection de Andrew Tate"];
        
    }

    /**
     * Generates initialization data for voitures : [name, description]
     * @return \\Generator
     */
    private static function MembreDataGenerator()
    {
        yield ["Andrew Tate","Sigma Male"];
        
    }

    /**
     * Generates initialization data for Moteur Electric : [label, description, sousmoteur=null]
     * @return \\Generator
     */
    private static function MoteurElecDataGenerator()
    {
        yield ["sous-type de Moteur électrique","Asynchrone",null];
        yield ["sous-type de Moteur électrique","Synchrone",null];
    }

    /**
     * Generates initialization data for Moteur Combustion Explosion : [label, description, sousmoteur=null]
     * @return \\Generator
     */
    private static function MoteurCombDataGenerator()
    {
        yield ["sous-type de Moteur à Combustion Explosion","Diesel",null];
        yield ["sous-type de Moteur à Combustion Explosion","Essence",null];
    }

    /**
     * Generates initialization data for Moteur Hybride : [label, description, sousmoteur=null]
     * @return \\Generator
     */
    private static function MoteurHybrideDataGenerator()
    {
        yield ["sous-type de Moteur Hybride","mHEV - mild-hybrids",null];
        yield ["sous-type de Moteur Hybride","HEV - les full-hybrids",null];
        yield ["sous-type de Moteur Hybride","PHEV - hybrides rechargeables",null];
    }

    public function load(ObjectManager $manager)
    {   
        // Gérer le jeu de données avec les catégories : 
        $repoDeMoteur = $manager->getRepository(Moteur::class);
        // création des moteurs "parents" (électrique et à combustion)
        // électrique : 
        $moteurelec = new Moteur();
        $moteurelec->setLabel("Type de Moteur");
        $moteurelec->setDescription("Moteur Électrique");
        //$moteurelec->setParent(null);

        // à combustion :
        $moteurcomb = new Moteur();
        $moteurcomb->setLabel("Type de Moteur");
        $moteurcomb->setDescription("Moteur à Combusion et éxplosion");
        //$moteurcomb->setParent(null);
        
        // Hybride :
        $moteurhyb = new Moteur();
        $moteurhyb->setLabel("Type de Moteur");
        $moteurhyb->setDescription("Moteur Hybride");
        //$moteurhyb->setParent(null);

        // création des sous moteurs pour chaque parent
        // Pour les élecs
        foreach (self::MoteurElecDataGenerator() as [$label, $description, $submot] ) {
            $moteurElec = new Moteur();
            $moteurElec->setLabel($label);
            $moteurElec->setDescription($description);
            $moteurElec->setParent($moteurelec);
            $moteurelec->addSousMoteur($moteurElec);
            $manager->persist($moteurElec);
        }
        $manager->persist($moteurelec);
        //$manager->flush();
        // Pour les Combustions:
        foreach (self::MoteurCombDataGenerator() as [$label, $description, $submot] ) {
            $moteurComb = new Moteur();
            $moteurComb->setLabel($label);
            $moteurComb->setDescription($description);
            $moteurComb->setParent($moteurcomb);
            $moteurcomb->addSousMoteur($moteurComb);
            $manager->persist($moteurComb);
        }
        //$manager->persist($moteurcomb);
        //$manager->flush();
        // Pour les hybrides
        foreach (self::MoteurHybrideDataGenerator() as [$label, $description, $submot] ) {
            $moteurHyb = new Moteur();
            $moteurHyb->setLabel($label);
            $moteurHyb->setDescription($description);
            $moteurHyb->setParent($moteurhyb);
            $moteurhyb->addSousMoteur($moteurHyb);
            $manager->persist($moteurHyb);
        }
        $manager->persist($moteurhyb);
        //$manager->flush();


        //

        // Géneration pour les voitures
        $repoDeVoiture = $manager->getRepository(Voiture::class);
        $voiture = new Voiture();
        $voiture->setDescription("Très belle voiture pour flex sur les pauvres");
        $voiture->setMarque("BUGATTI");
        $voiture->setModele("V3");      
        $voiture->setCouleur("ROUGE"); 
        $voiture->addMoteur($moteurcomb);
        //$moteurcomb->addVoiture($voiture);
        $manager->persist($moteurcomb);
        $manager->persist($voiture);
        // Savoir comment réutiliser des objets hors de la boucle. 
        //foreach (self::VoitureDataGenerator() as [$description, $marque,$modele,$couleur] ) {
         //   $voiture = new Voiture();
         //    $voiture->setDescription($description);
         //    $voiture->setMarque($marque);
         //    $voiture->setModele($modele);      
         //    $voiture->setCouleur($couleur); 
         //    $manager->persist($voiture);
        //}
        //$manager->flush();
        // Génération pour les collections
        $collectionAndrew = new CollectionDeVoiture();
        $collectionAndrew->setId(1);
        $collectionAndrew->setDescription("Collection de Andrew Tate");
        $collectionAndrew->addVoiture($voiture);
        $repoDeMembe = $manager->getRepository(Membre::class);

        foreach (self::MembreDataGenerator() as [$name, $description] ) {
            $membre = new Membre();
            $membre->setName($name);
            $membre->setDescription($description);
            $membre->addCollection($collectionAndrew);
            $collectionAndrew->setMembre($membre);
            $manager->persist($membre);
        }
        $manager->persist($collectionAndrew);
        // pour une galerie :
        $galerie = new Galerie();
        $galerie->setDescription("je ne sais pas à quoi sert cette entité");
        $galerie->setPublished(False);
        $galerie->setCreator($membre);
        $galerie->addVoiture($voiture);
        $manager->persist($galerie);
        $manager->flush();

        
    }
}
