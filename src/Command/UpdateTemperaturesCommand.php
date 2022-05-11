<?php
/**
 * City Temperature System
 */
namespace App\Command;

use App\Repository\CityRepository;
use App\Service\WeatherAPIInteractor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command updates all temperature data in the database for all existing City entities.
 *
 * @package   App\Command
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2022 MaxTheDragon
 * @license   Proprietary/Closed Source
 */
class UpdateTemperaturesCommand extends Command
{
    /**
     * Command name.
     */
    protected static $defaultName = 'app:update-temps';
    
    /**
     * Command description.
     */
    protected static $defaultDescription = 'Updates all temperature data for all existing cities.';
    
    /**
     * The Weather API interactor service.
     */
    private $apiInteractor;
    
    /**
     * The CityRepository object.
     */
    private $cityRepository;
    
    /**
     * The Doctrine ManagerRegistry object.
     */
    private $doctrine;

    /**
     * Creates a new instance of the UpdateTemperaturesCommand and sets up its dependencies.
     *
     * @param WeatherAPIInteractor $apiInteractor The Weather API interactor service.
     * @param CityRepository $cityRepository The CityRepository object.
     * @param ManagerRegistry $doctrine The Doctrine ManagerRegistry object.
     */
    public function __construct(WeatherAPIInteractor $apiInteractor, CityRepository $cityRepository, ManagerRegistry $doctrine)
    {
        $this->apiInteractor = $apiInteractor;
        $this->cityRepository = $cityRepository;
        $this->doctrine = $doctrine;

        parent::__construct();
    }
    
    /**
     * Configures this command.
     */
    protected function configure(): void
    {
        $this->setHelp('This command updates all temperature data in the database for all existing City entities...');
    }

    /**
     * Executes this command.
     *
     * @param InputInterface $input The input interface for reading user input.
     * @param OutputInterface $output The output interface for displaying feedback to the user.
     * @return int Exit status code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Fetch all cities.
        $cities = $this->cityRepository->findAll();
        
        // Retrieve the EntityManager object.
        $entityManager = $this->doctrine->getManager();
        
        // Loop through the cities.
        foreach ($cities as $city)
        {
            // Request current temperature data for this city.
            $temperature = $this->apiInteractor->requestTemperatureForCity($city);
            
            // Add it to the city.
            $city->addTemperature($temperature);
            
            // Persist it and flush changes to database.
            $entityManager->persist($temperature);
        }
        
        // Flush changes to database.
        $entityManager->flush();
        
        // Tell the user we're done.
        $output->writeln('Done!');
        
        // Return success status code.
        return Command::SUCCESS;
    }
}
