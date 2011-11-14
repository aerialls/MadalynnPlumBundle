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

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DeployCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('server', InputArgument::REQUIRED, 'The server name'),
                new InputOption('go', null, InputOption::VALUE_NONE, 'Do the deployment'),
                new InputOption('rsync-options', null, InputOption::VALUE_OPTIONAL, 'To options to pass to the rsync executable', '-azC --force --delete --progress')
            ))
            ->setName('project:deploy')
            ->setDescription('Deploys a project to another server')
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
        $deployer = $this->container->get('deployer');

        $serverName = $input->getArgument('server');

        if (false === $deployer->hasServer($serverName)) {
            throw new \InvalidArgumentException(sprintf('The server "%s" does not exist.', $serverName));
        }

        $server = $deployer->getServer($serverName);

        $dryRun = $input->getOption('go') ? '' : '--dry-run';
        $options = $input->getOption('rsync-options');

        $command = sprintf('rsync %s %s -e %s ../ %s',
                $dryRun,
                $options,
                $server->getSSHInformations(),
                $server->getLoginInformations()
        );

        $process = new Process($command);

        //FIXME: Not working for the moment...
        $process->run(function($type, $line) use ($output) {
            $output->writeln('$line');
        });
    }
}

