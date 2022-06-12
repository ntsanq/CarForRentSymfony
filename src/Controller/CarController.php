<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route('/car', name: 'app_car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }

    /**
     * @Route("/addCar", name="add_car")
     */
    public function addCar(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $product = new Car();
        $product->setName('Vis');
        $product->setDescription('This car is good!');
        $product->setPrice(122);
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }

    /**
     * @Route("/car/{id}", name="show_car")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $car = $doctrine->getRepository(Car::class)->find($id);
        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }
        return new Response("The car with the id $id is: " . $car->getName());
    }

    /**
     * @Route("/car/entity/{id}", name="entity_show_car")
     */
    public function showByEntity(Car $car): Response
    {
        return new Response("The car with the id is: " . $car->getName());
    }
}
