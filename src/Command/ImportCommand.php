<?php

namespace App\Command;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImportCommand extends Command
{
    protected EntityManagerInterface $entityManager;

    protected ParameterBagInterface $parameterBag;

    protected static $defaultName = 'app:import';

    public function __construct(EntityManagerInterface  $entityManager, ParameterBagInterface $parameterBag)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import customers from csv.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Parsing %s ...', '/src/Data/timbres2020.csv'));

        $sourceFilepath = $this->parameterBag->get('kernel.project_dir').'/src/Data/timbres2020.csv';
        $file = fopen($sourceFilepath, 'r');


        $batchNumber = 1000;

        $batch  = 0;

        while($row = fgetcsv($file)) {

            if ($batch > $batchNumber) {
                $endTime = microtime(true);
                $io->writeln(sprintf("Execution Time: %s sec", $endTime - $startTime));
                $io->writeln(sprintf("Memory: %s mb", memory_get_usage()/1000000));
                $batch = 0;
            }

            $pseudo = $row[2];
            $customer = $this->entityManager->getRepository(Customer::class)->findOneBy([
                'pseudo' => $pseudo,
                ]);

            if(!$customer) {
                $this->createNewCustomerAndPersist($pseudo);
            }

            $batch++;
        }

        fclose($file);

        $endTime = microtime(true);
        printf("Execution Time: %s sec\n", $endTime - $startTime);
        printf("Memory: %s mb\n", memory_get_usage()/1000);

        return Command::SUCCESS;
    }

    protected function createNewCustomerAndPersist(String $pseudo) {
        $entity = new Customer();
        $entity
            ->setPseudo($pseudo)
            ->setFirstname('undefined')
            ->setLastname('undefined');

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}