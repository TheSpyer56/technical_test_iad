<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/contact/create', name: 'create_contact', methods: 'POST')]
    public function create_contact(Request $request): Response
    {
        $parameter = json_decode($request->getContent(), true);
        $contact = new Contact(...$parameter);

        $this->insertOnDb($contact);
        return $this->json('Inserted Successfully', 201);
    }

    #[Route('/contact/{id}', name: 'read_contact', methods: 'GET')]
    public function read_contact($id): Response {
        $data = $this->findOneContactById($id);

        if (!$data)
            return $this->json("No contact exist on the database with the id: ".$id, 404);
        $res = $this->fromDoctrineToArray($data);
        return $this->json($res, 200);
    }

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
    
    #[Route('/contact/{id}', name: 'delete_contact', methods: 'DELETE')]
    public function delete_contact($id): Response {
        $data = $this->findOneContactById($id);

        if (!$data)
            return $this->json("No contact exist on the database with the id: ".$id, 404);
        $this->deleteOnDb($data);
        return $this->json('Deleted Successfully', 200);
    }

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
}
