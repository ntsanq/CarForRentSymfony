<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route (
     *     "/",
     *     name = "app_home"
     * )
     * @param CarRepository $carRepository
     * @return Response
     */
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();
        return $this->render('home/index.html.twig', ['cars' => $cars]);
    }

    /**
     * @Route(
     *     "/home/key",
     *     name = "home_showKey"
     * )
     */
    public function showKey(ParameterBagInterface $parameterBag): Response
    {
        $s3key = $parameterBag->get('s3Secret');
        return $this->render('/home/key.html.twig', [
            's3key' => $s3key
        ]);
    }
}
