<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\DeployBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DeployCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        
        $this
            ->setName('project:deploy')
            ->setDescription('Deploys a project to another server')
            ->addArgument('server', InputArgument::REQUIRED, 'The server name')
            ->addOption('go', null, InputOption::VALUE_NONE, 'Do the deployment')
            ->addOption('rsync-options', null, InputOption::VALUE_OPTIONAL, 'To options to pass to the rsync executable', '-azC --force --delete --progress')
            ->setHelp(<<<EOF
The <info>project:deploy</info> command deploys a project on a server:

  <info>php app/console project:deploy production</info>

The server must be configured in <comment>app/config/config.yml</comment>:

  madalynn_deploy
    host: www.example.com
    port: 22
    user: fabien
    dir: /var/www/sfblog/

To automate the deployment, the task uses rsync over SSH.
You must configure SSH access with a key or configure the password
in <comment>app/config/config.yml</comment>.

By default, the task is in dry-mode. To do a real deployment, you
must pass the <comment>--go</comment> option:

  <info>php app/console project:deploy --go production</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('server');
        
        $deployer = $this->getContainer()->get('deployer');
        
        $serverName = $input->getArgument('server');
        
	$output->writeln($serverName);
        
        if (false === $deployer->hasServer($serverName)) {
            throw new \InvalidArgumentException(sprintf('The server "%s" does not exist.', $serverName));
        }
        
        $output->writeln('OK LETS DANCE!!!');
        
        $server = $deployer->getServer($serverName);
        
        $dryRun = $input->getOption('go') ? '' : '--dry-run';
	$options = $input->getOption('rsync-options');
        
        $command = sprintf('rsync %s %s -e %s ./ %s',
                $dryRun, 
                $options, 
                $server->getSSHInformations(), 
                $server->getLoginInformations()
        );
        
        $output->writeln($command);
        
        $process = new Process($command);
        
        $process->run(function($type, $line) use ($output) {
            $output->writeln($line);
        });
    }
    
}

