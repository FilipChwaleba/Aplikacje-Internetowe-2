<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{


    public function __construct(
        private readonly WeatherUtil $weatherUtil
    )
    {}

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter] bool $twig = false,
        #[MapQueryParameter] bool $preview = false,
    ): Response
    {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            $template = 'weather_api/index.' . $format . '.twig';
            $data = [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ];

            if ($preview) {
                return $this->render($template, $data);
            }

            $response = $this->renderBlock($template, 'payload', $data);
            $response->headers->set('Content-Type',
                $format === 'json' ? 'application/json' : 'text/plain; charset=UTF-8'
            );

            return $response;
        }
        if ($format === 'json') {
            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(Measurement $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements),
            ]);
        }
        if ($format !== 'csv') {
            return $this->json(['error' => 'Use format=json or format=csv.'], 400);
        }
        $csv = "city,country,date,celsius,fahrenheit\n";

        $csv .= implode("\n", array_map(fn(Measurement $m) => implode(",", [
            $city,
            $country,
            $m->getDate()->format('Y-m-d'),
            $m->getCelsius(),
            $m->getFahrenheit(),
        ]), $measurements));

        return new Response($csv, Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=UTF-8',]);
    }
}

