<?php
namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Membre;

class UserFixtures extends Fixture
{

    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        $this->LoadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [
            $email,
            $plainPassword,
            $role
        ]) {
            $user = new User();
            $encodedPassword = $this->userPasswordHasherInterface->hashPassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);

            $roles = array();
            $roles[] = $role;
            $user->setRoles($roles);
            
            if($email == 'ANDREWTATE@localhost'){
                $membre_andrew = $manager->getRepository(Membre::class)->findAll()[0];
                $user->setMembre($membre_andrew);
                $membre_andrew->setUser($user);
                $manager->persist($membre_andrew);
            }

            if($email == 'BATMAN@localhost'){
                $membre_batman = $manager->getRepository(Membre::class)->findAll()[1];
                $user->setMembre($membre_batman);
                $membre_batman->setUser($user);
                $manager->persist($membre_batman);
            }
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData()
    {

        yield [
            'anna@localhost',
            'anna',
            'ROLE_ADMIN',
        ];
        yield [
            'ANDREWTATE@localhost',
            'ANDREW',
            'ROLE_USER',
        ];
        yield [
            'BATMAN@localhost',
            'BATMAN',
            'ROLE_USER',
        ];

    }
}