<?php

namespace RestaurantBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MenuVerifaicationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command
            ->setName('restaurant:menu:verification')

            // the short description shown while running "php bin/console list"
            ->setDescription('Verification of non empty menu.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to verify if a menu has no dishes in it else it send a mail to an administrator")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('restaurant.mailer')->startMenuVerification();

        $output->write('Menu verification done !');
    }
}
