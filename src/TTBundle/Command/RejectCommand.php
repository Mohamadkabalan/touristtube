<?php
namespace TTBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RejectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:rejectLines')
            ->setDescription('Executes the RejectLines cron');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Starting the cron");
       // Call the method here
        $cron = $this->getApplication()->getKernel()->getContainer()->get('SabreServices');
        $cron->paymentLastUpdateChecker('cronjob');

        $output->writeln("Cron completed");
    }
}
?>