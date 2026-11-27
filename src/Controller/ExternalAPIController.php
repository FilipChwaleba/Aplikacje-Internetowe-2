<?php

namespace App\Controller;

use App\Form\ExternalAPIType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ExternalAPIController extends AbstractController
{
    #[Route('/externalapi', name: 'app_external_api', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        HttpClientInterface $httpClient
    ): Response {
        $form = $this->createForm(ExternalAPIType::class);
        $weatherData = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $response = $httpClient->request(
                'GET',
                'https://api.open-meteo.com/v1/forecast',
                [
                    'query' => [
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'daily' => 'temperature_2m_max,temperature_2m_min,precipitation_sum',
                        'timezone' => 'UTC',
                    ],
                ]
            );

            $weatherData = $response->toArray();
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form->createView(),
            'weather_data' => $weatherData,
        ]);
    }
}
