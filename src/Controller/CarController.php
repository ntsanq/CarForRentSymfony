<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route (
 *      "/car",
 *     name = "car_"
 * )
 */
class CarController extends AbstractController
{
    /**
     * @Route (
     *      "/",
     *     name = "index"
     * )
     */
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }

    /**
     * @Route (
     *
     * )
     */
    public function new()
    {
        $product = new Car();
    }

    /**
     * @Route("/add", name="add_by_entity_manager")
     */
    public function addCar(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $car = new Car();
        $car->setName('Vis');
        $car->setDescription('This car is good!');
        $car->setPrice(122);
        $car->setImg('https://sdhd.sdfjfsod/dsfjsdfiudsf');
        $entityManager->persist($car);
        $entityManager->flush();

        return new Response('Saved new product with id ' . $car->getId());
    }

    /**
     * @Route(
     *     "/all",
     *     name = "show_all"
     * )
     */
    public function showAllCars(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();
        return $this->render('home/index.html.twig', ['cars' => $cars]);
    }

    /**
     * @Route("/{id}", name="show_by_id")
     */
    public function showCarById(ManagerRegistry $doctrine, int $id): Response
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
     * @Route("/car/entity/{id}", name="entity_show")
     */
    public function showByEntity(Car $car): Response
    {
        return new Response("The car with the id is: " . $car->getName());
    }

}
