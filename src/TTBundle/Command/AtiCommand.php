<?php

namespace TTBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AtiCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('cron:atiUpdatePackages')
            ->setDescription('Updates DB with latest tours of ATI based on city ids')
            ->addArgument('dealtype', InputArgument::REQUIRED, '2 for tours or 3 for activities or 5 for attractions or 6 for updating availability ')
            ->addArgument('serviceType', InputArgument::REQUIRED, ' 1 for AtiServices or 3 for CityDiscoveryServices or 5 for Deals Services');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(date('c').' Starting the cron');

        $serviceType = $input->getArgument('serviceType');

        if ($serviceType == 1) {
            $serviceName = 'AtiServices';
        } elseif ($serviceType == 3) {
            $serviceName = 'CityDiscoveryServices';
        }elseif ($serviceType == 5) {
            $serviceName = 'DealServices';
        }

        if (isset($serviceName) && !empty($serviceName)) {
            $cron     = $this->getApplication()->getKernel()->getContainer()->get($serviceName);
            $dealtype = $input->getArgument('dealtype');

            if ($dealtype == 2) {
                $output->writeln(date('c').' deal type selected is tours');
                $cron->getCityPackages(true);
            } elseif ($dealtype == 3) {
                $output->writeln(date('c').' deal type selected is activities');
                $cron->getCityActivities(true);
            } elseif ($dealtype == 5) {
                $output->writeln(date('c').' deal type selected is attractions');
                $cron->getAttractions(true);
            } elseif ($dealtype == 6) {
                $output->writeln(date('c').' update Activities Availability from City Discovery that were published');
                $cron->updateActivitiesAvailability(true);
            } elseif ($dealtype == 9) {
                $output->writeln(date('c').' getting all types for city discovery ');
                $cron->getCityDiscoveryActivities(true);
            } elseif ($dealtype == 8) {
                $output->writeln(date('c').' getting all types for city discovery ');
                $cron->getCityDiscoveryImages(true);
            } else {
                $output->writeln(date('c').' Please enter an input deal type: 2 for tours, 3 for activities, 5 for attractions');
            }
        } else {
            $output->writeln(date('c').' Please enter an input deal service:  1 for AtiServices or 3 for CityDiscoveryServices ');
        }

        $output->writeln(date('c').' Cron completed');
    }
}
?>