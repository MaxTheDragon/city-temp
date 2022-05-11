<?php
/**
 * City Temperature System
 */
namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Service\WeatherAPIInteractor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * City controller class that handles the frontend display of a city's temperature
 * statistics.
 *
 * @extends AbstractController
 *
 * @package   App\Controller
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2022 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class CityController extends AbstractController
{
    /**
     * The allowed characters in a city name.
     */
    public const ALLOWED_CITY_CHARS = 'A-Za-z 0-9\-\'';
    
    /**
     * Shows the current temperature and its history for a given city.
     * If the city is requested for the first time, it'll be added to the database and
     * an initial Temperature entity will be made for it, after which it'll be periodically
     * updated to track temperature history.
     *
     * @param string $cityName The name of the city of which to display data.
     * @param CityRepository $cityRepository The CityRepository using which data is stored and retrieved.
     * @return Response The rendered template response.
     * @Route("/{cityName}")
     */
    public function index(
        string $cityName,
        CityRepository $cityRepository,
        ManagerRegistry $doctrine,
        WeatherAPIInteractor $weatherAPI
    ): Response
    {
        // Sanitize input.
        $cityName = preg_replace('/[^'.self::ALLOWED_CITY_CHARS.'\-]/', '', $cityName);
        
        // Retrieve city from database.
        $city = $cityRepository->findByName($cityName);
        
        // If the city does not yet exist...
        if (is_null($city))
        {
            // ... create it.
            $city = $cityRepository->create($cityName);
            
            // Also create an initial Temperature entity for this new city and store it in the database.
            $temperature = $weatherAPI->requestTemperatureForCity($city);
            
            // If it's null, then probably the city name provided by the user wasn't valid.
            // In that case, just issue a 404.
            if (is_null($temperature))
                throw $this->createNotFoundException('This city does not exist in the world.');
            
            // Attach it to the City entity object.
            $city->addTemperature($temperature);
            
            // Persist it and flush changes to database.
            $entityManager = $doctrine->getManager();
            $entityManager->persist($temperature);
            $entityManager->flush();
        }
        
        // Render and return template.
        return $this->render('city.html.twig', ['city' => $city]);
    }
}
