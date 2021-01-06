<?php

namespace App\Command;

use _HumbugBox221ad6f1b81f\Nette\Utils\DateTime;
use App\Entity\Customer;
use App\Entity\Transaction;
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
    private const FILE_NAME = 'TIMBRES.csv';

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
        $io->title(sprintf('Parsing %s ...', self::FILE_NAME));

        $sourceFilepath = $this->parameterBag->get('kernel.project_dir').'/src/Data/TIMBRES.csv';
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

            try {
                $date = new \DateTime($row[0]);
                $pseudo = $row[2];
                $price = self::parseFloat($row[3]);
                $commission = self::parseFloat($row[4]);
            } catch (\Exception $e) {
                continue;
            }

            $customer = $this->findOrCreateCustomer($pseudo);

            $this->createNewTransactionAndPersist($date, $customer, $price, $commission);

            $this->entityManager->flush();

            $batch++;
        }

        fclose($file);

        $endTime = microtime(true);
        printf("Execution Time: %s sec\n", $endTime - $startTime);
        printf("Memory: %s mb\n", memory_get_usage()/1000);

        return Command::SUCCESS;
    }

    static function parseFloat(String $input): float {
        return floatval(str_replace([','], '.', $input));
    }

    protected function findOrCreateCustomer(String $pseudo): Customer {
        $customer = $this->entityManager->getRepository(Customer::class)->findOneBy([
            'pseudo' => $pseudo,
        ]);

        if(!$customer) {
            $customer = new Customer();
            $customer->setPseudo($pseudo);

            $this->entityManager->persist($customer);
        }

        return $customer;
    }

    protected function createNewTransactionAndPersist(
        \DateTime $date,
        Customer $customer,
        float $price,
        float $tax,
    ): void {
        $entity = new Transaction();
        $entity
            ->setDate($date)
            ->setPrice($price)
            ->setTax($tax)
            ->setCustomer($customer)
            ->setIsClosed(true);

        $this->entityManager->persist($entity);
    }
}