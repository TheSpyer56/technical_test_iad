<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Contact;

use function PHPUnit\Framework\assertTrue;

class ContactEntityTest extends TestCase
{
    public function testSetterChangeValueCorrectly(): void {
        $test_name      = "TestName";
        $test_surname   = "TestSurname";
        $test_email     = "test.email@toto.foo";
        $test_address   = "9 Toto Road, Foo City, US";
        $test_phone     = "+33 7 88 34 27 87";
        $test_age       = 42;

        $contact = new Contact(
            "Foo",
            "Toto",
            "toto.foo@iad.org",
            "9 Rue de la Paix, Paris, France",
            "+261 34 56 771 23",
            30
        );
        
        $contact->setName($test_name)
                ->setSurname($test_surname)
                ->setEmail($test_email)
                ->setAddress($test_address)
                ->setPhone($test_phone)
                ->setAge($test_age);

        $this->assertTrue($contact->getName() === $test_name);
        $this->assertTrue($contact->getSurname() === $test_surname);
        $this->assertTrue($contact->getEmail() === $test_email);
        $this->assertTrue($contact->getAddress() === $test_address);
        $this->assertTrue($contact->getPhone() === $test_phone);
        $this->assertTrue($contact->getAge() === $test_age);
    }

    public function testGetterGiveRightValue(): void {
        $test_name      = "TestName";
        $test_surname   = "TestSurname";
        $test_email     = "test.email@toto.foo";
        $test_address   = "9 Toto Road, Foo City, US";
        $test_phone     = "+33 7 88 34 27 87";
        $test_age       = 42;

        $contact = new Contact(
            "Foo",
            "Toto",
            "toto.foo@iad.org",
            "9 Rue de la Paix, Paris, France",
            "+261 34 56 771 23",
            30
        );

        $this->assertFalse($contact->getName() === $test_name);
        $this->assertFalse($contact->getSurname() === $test_surname);
        $this->assertFalse($contact->getEmail() === $test_email);
        $this->assertFalse($contact->getAddress() === $test_address);
        $this->assertFalse($contact->getPhone() === $test_phone);
        $this->assertFalse($contact->getAge() === $test_age);
    }
}
