<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    // BASIC CRUD ROUTES
    
    /**
     * Create a contact from payload and insert it into database
     */

    #[Route('/contact/create', name: 'create_contact', methods: 'POST')]
    public function create_contact(Request $request): Response
    {
        $parameter = json_decode($request->getContent(), true);
        $contact = new Contact(...$parameter);

        $this->insertOnDb($contact);
        return $this->json('Inserted Successfully', 201);
    }

    /**
     * Read the contact corresponding to $id into database and return it with JSON format
     */

    #[Route('/contact/{id}', name: 'read_contact', methods: 'GET')]
    public function read_contact($id): Response {
        $data = $this->findOneContactById($id);

        if (!$data)
            return $this->json("No contact exist on the database with the id: ".$id, 404);
        $res = $this->fromDoctrineToArray($data);
        return $this->json($res, 200);
    }

    /**
     * Update the contact corresponding to $id into database
     */

    #[Route('/contact/{id}', name: 'update_contact', methods: 'PUT')]
    public function update_contact(Request $request, $id): Response {
        $parameter = json_decode($request->getContent(), true);
        $contact = new Contact(...$parameter);
        $data = $this->findOneContactById($id);
        if (!$data)
            return $this->json("No contact exist on the database with the id: ".$id, 404);
        $this->updateContactWithReqParams($data, $parameter);
        $this->insertOnDb($data);
        return $this->json('Updated Successfully', 200);
    }
    
    /**
     * Delete contact corresponding to $id on database.
     */

    #[Route('/contact/{id}', name: 'delete_contact', methods: 'DELETE')]
    public function delete_contact($id): Response {
        $data = $this->findOneContactById($id);

        if (!$data)
            return $this->json("No contact exist on the database with the id: ".$id, 404);
        $this->deleteOnDb($data);
        return $this->json('Deleted Successfully', 200);
    }

    # ROUTES TO ADVANCE RESEARCH

    /**
     * This route returns an array of contacts whose values in the $field column contain the value $contains
     */

    #[Route('/contact/search/{field}/{contains}', name: 'search_contact', methods: 'GET', priority: 1)]
    public function research_contact(EntityManagerInterface $em, $field, $contains): Response {
        $data = $this->searchContainsInField($em, $field, $contains);
        if (!$data)
            return $this->json("No contact match with given filter", 404);
        foreach ($data as $d) {
            $array[] = $this->fromDoctrineToArray($d);
        }
        return $this->json($array, 200);
    }

    /**
     * This route returns an array containing the last $number of contacts added to the database.
     * If $number is not specified, the route returns an array containing only the last registered contact.
     */

    #[Route('/contact/last/{number}', name: 'last', methods: 'GET', priority: 1)]
    public function last(EntityManagerInterface $em, int $number = 1): Response {
        $repository = $em->getRepository(Contact::class);
        $data = $repository->findLast($number);

        if (!$data)
            return $this->json("Database is empty", 404);
        foreach ($data as $d) {
            $array[] = $this->fromDoctrineToArray($d);
        }
        return $this->json($array, 200);
    }

    # ROUTES WHOSE HANDLE ALL RESSOURCES IN CONTACT TABLE

    /**
     * This route return a JSON array who contain all contact in database
     */

    #[Route('/contact', name: 'read_all_contact', methods: 'GET')]
    public function read_all_contact(): Response {
        $data = $this->getAllContact();

        if (!$data)
            return $this->json('Contact database is empty !', 404);
        foreach ($data as $d) {
            $res[] = $this->fromDoctrineToArray($d);
        }
        return $this->json($res, 200);
    }

    /**
     * This route dump the database: contact table is fully purge
     * /!\ Warning: Use this route only if you know what you do /!\
     */

    #[Route('/contact/dump', name: 'dump_contact', methods: 'DELETE', priority: 1)]
    public function dump_contact(): Response {
        $data = $this->getAllContact();

        if (!$data)
            return $this->json('Contact database is empty !', 404);
        foreach ($data as $d) {
            $this->deleteOnDb($d);
        }
        return $this->json('Database dumped successfully !', 200);
    }

    // FUNCTIONS

    private function findOneContactById($id) {
        $data = $this->doctrine->getRepository(Contact::class)->find($id);

        return $data;
    }

    private function getAllContact() {
        $data = $this->doctrine->getRepository(Contact::class)->findAll();

        return $data;
    }

    private function updateContactWithReqParams(&$data, $parameters) {
        $data->setName($parameters['name']);
        $data->setSurname($parameters['surname']);
        $data->setEmail($parameters['email']);
        $data->setAddress($parameters['address']);
        $data->setPhone($parameters['phone']);
        $data->setAge($parameters['age']);
    }

    private function insertOnDb($data) {
        $em = $this->doctrine->getManager();

        $em->persist($data);
        $em->flush();
    }

    private function deleteOnDb($data) {
        $em = $this->doctrine->getManager();

        $em->remove($data);
        $em->flush();
    }

    private function fromDoctrineToArray($data): array {
        $array = [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'surname' => $data->getSurname(),
            'email' => $data->getEmail(),
            'address' => $data->getAddress(),
            'phone' => $data->getPhone(),
            'age' => $data->getAge()
        ];
        return $array;
    }

    private function searchContainsInField(EntityManagerInterface $em, $field, $contains) {
        $repository = $em->getRepository(Contact::class);
        switch ($field) {
            case 'name':
                return  $repository->findByNameContains($contains);
                break;
            case 'surname':
                return $repository->findBySurnameContains($contains);
                break;
            case 'email':
                return $repository->findByEmail($contains);
                break;
            case 'address':
                return $repository->findByAddressContains($contains);
                break;
            case 'phone':
                return $repository->findByPhoneContains($contains);
                break;
            case 'age':
                return $repository->findByAgeContains($contains);
                break;
            default:
                return null;
                break;
        }
    }
}
