<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends WebTestCase
{
    public function testCreateContact()
    {
        $client = static::createClient();
        $payload = [
            'name' => 'Foo',
            'surname' => 'Toto',
            'email' => 'toto.foo@iad.org',
            'address' => '9 Rue de la Paix, Paris, France',
            'phone' => '+261 34 56 771 23',
            'age' => 30
        ];

        $client->request('POST',
                        '/contact/create',
                        $payload
                );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testCreateContactBadBody()
    {
        $client = static::createClient();

        $payload = [
            'surname' => 'Toto',
            'name' => 'Foo',
            'email' => 28,
            'address' => '9 Rue de la Paix, Paris, France',
            'phone' => '+261 34 56 771 23',
            'age' => "18"
        ];

        $client->request('POST',
                        '/contact/create',
                        $payload
                );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
/*
    public function testReadContact()
    {
        $client = static::createClient();

        $client->request('GET', '/contact');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK );
    }

    
    public function testUpdateContact()
    {
        $client = static::createClient();

        $client->request('PUT', '/contact:id');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK );
    }
     
    public function testDeleteContact()
    {
        $client = static::createClient();

        $client->request('GET', '/contact:id');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK );
    }*/
}
