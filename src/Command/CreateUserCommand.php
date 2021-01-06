<?php

namespace App\Command;

use App\Entity\Transaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-user';

    protected function configure()
    {
      $this
          // the short description shown while running "php bin/console list"
          ->setDescription('Creates a new user.')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp('This command allows you to create a user...')
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $transaction = new Transaction();
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}