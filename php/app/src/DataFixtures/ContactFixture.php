<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixture extends Fixture
{
    private $faker;

    public function __construct() {

        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $manager->persist($this->getContact());
        }
        $manager->flush();
    }

    private function getContact() {

        return new Contact(
            $this->faker->lastName(),
            $this->faker->firstName(),
            $this->faker->email(),
            $this->faker->address(),
            $this->faker->phoneNumber(),
            $this->faker->numberBetween(8, 87)
        );
    }
}
