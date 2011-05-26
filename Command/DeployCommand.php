<?php

namespace Madalynn\DeployBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('project:deploy')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}

