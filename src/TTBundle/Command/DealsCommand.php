<?php

namespace TTBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DealsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('cron:dealsUpdateRecords')
            ->setDescription('Updates DB with latest tours Changes from different vendors')
            ->addArgument('serviceType', InputArgument::REQUIRED, '1 for updateActivitiesAvailability, 2 for getCityDiscoveryActivities, 3 for getCityDiscoveryImages');
    }
/*
 * To run the new command cron:
 * From app directory:
 * for tt go to : /home/tt/www/app/
 * for deals master: /data/www/packages/app/
 *
 * 
 * update Activities Availability from City Discovery by updating the published column in deal deatails.
 * php console cron:dealsUpdateRecords 1 &>/data/log/deals/scripts/packages.log &
 *
 * getting all deals for all types like tours, activities, attractions ... from city discovery and insert them to our DB
 * php console cron:dealsUpdateRecords 2 &>/data/log/deals/scripts/packages.log &
 *
 *
 * getting all images for city discovery and insert them to our DB
 * php console cron:dealsUpdateRecords 3 &>/data/log/deals/scripts/packages.log &
 *
 *
 *
 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(date('c').' Starting the cron');
        
        $cron     = $this->getApplication()->getKernel()->getContainer()->get('DealServices');
        $serviceType = $input->getArgument('serviceType');
        
        if ($serviceType == 1) {
            $output->writeln(date('c').' update Activities Availability from City Discovery by updating the published column in deal deatails. ');
            $cron->updateActivitiesAvailability(true);
        } elseif ($serviceType == 2) {
            $output->writeln(date('c').' getting all deals for all types like tours, activities, attractions ... from city discovery and insert them to our DB');
            $cron->getCityDiscoveryActivities(true);
        } elseif ($serviceType == 3) {
            $output->writeln(date('c').' getting all images for city discovery and insert them to our DB ');
            $cron->getCityDiscoveryImages(true);
        } else {
            $output->writeln(date('c').' Please enter an input service type: 1 for updateActivitiesAvailability, 2 for getCityDiscoveryActivities, 3 for getCityDiscoveryImages');
        }

        $output->writeln(date('c').' Cron completed');
    }
}
?>