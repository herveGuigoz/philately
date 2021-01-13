<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    public function __construct(
        private UserPasswordEncoderInterface $passwordEncoder,
        private String $username,
        private String $password
    ){}

    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setUsername($this->username);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            $this->password,
        ));

        $manager->persist($admin);

        $manager->flush();
    }
}
