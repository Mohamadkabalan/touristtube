<?php
namespace TTBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CmsCronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:cmschecker')
            ->setDescription('check if any ticket with cms is issued');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Starting the cron");
       // Call the method here
        $cron = $this->getApplication()->getKernel()->getContainer()->get('SabreServices');
        $cron->cmsIssueChecker('cronjob');

        $output->writeln("Cron completed");
    }
}
?>