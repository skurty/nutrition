<?php

namespace Skurty\NutritionBundle\Console\Command;

// use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Knp\Command\Command;

class CalorieCommand extends Command
{
    protected function configure()
    {
        $this->setName('calories:update')
                ->setDescription('Update calories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();

        $repository = new \Skurty\NutritionBundle\Repository\CalorieRepository($app['db']);

        $startDate = $repository->getLastDate();

        $endDate = date('Y-m-d', strtotime('-1 day'));

        if ($startDate <= $endDate) {
            $repository->update($startDate, $endDate);
        }

        $output->writeln('OK');
    }
}