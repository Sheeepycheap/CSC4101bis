<?php

namespace App\DataFixtures;

use App\Entity\CollectionDeVoiture;
use App\Entity\Galerie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Voiture; 
use App\Entity\Membre; 
use App\Entity\Moteur;
use App\Entity\User;
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
        yield ["Andrew Tate","Sigma Male","ANDREWTATE@localhost"];

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

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
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

        $batmobile = new Voiture();
        $batmobile->setDescription("BATMOBILE");
        $batmobile->setMarque("WAYNE ENTREPRISE");
        $batmobile->setModele("THE DARK KNIGHT");      
        $batmobile->setCouleur("NWAAR"); 
        $batmobile->addMoteur($moteurcomb);
        $manager->persist($moteurcomb);
        $manager->persist($voiture);
        $manager->persist($batmobile);
        // Savoir comment réutiliser des objets hors de la boucle. 

        $collectionAndrew = new CollectionDeVoiture();
        $collectionAndrew->setId(1);
        $collectionAndrew->setDescription("Collection des voitures à combustion de Andrew Tate");
        $collectionAndrew->addVoiture($voiture);

        $collectionBatman = new CollectionDeVoiture();
        $collectionBatman->setId(2);
        $collectionBatman->setDescription("Collection des voitures utilisées par Batman pour tabasser les méchants pas bo");
        $collectionBatman->addVoiture($batmobile);

        //andrew membre
        $andrew_membre = new Membre();
        $andrew_membre->setName('ANDREW TATE');
        $andrew_membre->setDescription("Sigma Male");
        $andrew_membre->addCollection($collectionAndrew);
        $collectionAndrew->setMembre($andrew_membre);
        $manager->persist($andrew_membre);
        $manager->persist($collectionAndrew);
        // batman membre
        $batman_membre = new Membre();
        $batman_membre->setName("BATMAN");
        $batman_membre->setDescription("JUSTICIER DE L'OMBRE");
        $batman_membre->addCollection($collectionBatman);
        $collectionBatman->setMembre($batman_membre);
        $manager->persist($batman_membre);
        $manager->persist($collectionBatman);
        // pour une galerie :
        $galerie_andrew = new Galerie();
        $galerie_andrew->setDescription("Galerie montrant les BUGGATIS de SIEUR TOPG");
        $galerie_andrew->setPublished(True);
        $galerie_andrew->setCreator($andrew_membre);
        $galerie_andrew->addVoiture($voiture);
        $manager->persist($galerie_andrew);

        $galerie_batman = new Galerie();
        $galerie_batman->setDescription("Batman expose ses véhicules favoris");
        $galerie_batman->setPublished(False);
        $galerie_batman->setCreator($batman_membre);
        $galerie_batman->addVoiture($batmobile);
        $manager->persist($galerie_batman);

        // $user = new User();
        // $user->setEmail("topg@localhost");
        // $user->setMembre($membre);
        // $user->setPassword('topg');
        // $user->setRoles(['ROLE_USER']);
        // $manager->persist($user);

        
        $manager->flush();
    }
}
