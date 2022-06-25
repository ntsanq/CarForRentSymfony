<?php

namespace App\Controller\API\Car;

use App\Service\CarService;
use App\Traits\JsonResponseTrait;
use App\Transformer\CarTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Request\CarRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class CarController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('/cars', name: 'car_index')]
    public function index(
        CarTransformer     $carTransformer,
        CarRequest         $carRequest,
        Request            $request,
        CarService         $carService,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $params = $request->query->all();
        $carData = $carRequest->fromArray($params);
        $errors = $validator->validate($carRequest);
        if (count($errors) > 0) {
            return $this->error($errors);
        }
        $cars = $carService->findAll($carData);
        $data = $carTransformer->toArray($cars);

        return $this->success($data);
    }

}
