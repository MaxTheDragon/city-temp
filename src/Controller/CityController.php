<?php
/**
 * City Temperature System
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * City controller class that handles the frontend display of a city's temperature
 * statistics.
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
     * If the city is requested for the first time, an initial log record will be made
     * for it, after which it'll be periodically updated to track temperature history.
     *
     * @return Response The rendered template response.
     * @Route("/{city}")
     */
    public function index(string $city): Response
    {
        // Sanitize input
        preg_replace('/[^'.self::ALLOWED_CITY_CHARS.'\-]/', '', $city);
        
        
        
        
        return $this->render('city.html.twig', [
            'city' => $city,
        ]);
    }
}
