<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $password)
    {
        $this->hasher = $password;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User;
        $user1->setEmail("admin@gmail.com")
        ->setRoles(["ROLE_ADMIN"])
        ->setPassword($this->hasher->hashPassword($user1, "123456"));

        $manager->persist($user1);

        $user2 = new User;
        $user2->setEmail("user2@gmail.com")
        ->setRoles(["ROLE_USER"])
        ->setPassword($this->hasher->hashPassword($user2, "123456"));

        $manager->persist($user2);

        $manager->flush();
    }
}
