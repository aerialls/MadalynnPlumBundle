<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2012 Julien Brochet <mewt@madalynn.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Madalynn\Bundle\PlumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plum:deploy')
            ->setDescription('Deploys a project to another server')
            ->addArgument('server', InputArgument::REQUIRED, 'The server name')
            ->addArgument('deployers', InputArgument::OPTIONAL, 'A list of deployer name', 'rsync')
            ->setHelp(<<<EOF
The <info>plum:deploy</info> command deploys a project on a server:

  <info>php app/console plum:deploy production</info>

The server must be configured in <comment>app/config/deployment.yml</comment>:

    servers:
        production:
            host: www.mywebsite.com
            port: 22
            user: julien
            dir: /var/www/sfblog/
            options:
                dry_run: false

To automate the deployment, the task uses rsync over SSH.
You must configure SSH access with a key or configure the password
in <comment>app/config/deployment.yml</comment>.
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server    = $input->getArgument('server');
        $deployers = explode(',', $input->getArgument('deployers'));

        foreach ($deployers as $deployer) {
            $this->deploy($server, trim($deployer), $output);
        }
    }

    /**
     * Deploys the application to another server using a deployer.
     *
     * @param string          $server   The server name
     * @param string          $deployer The deployer name
     * @param OutputInterface $output   The output object
     */
    protected function deploy($server, $deployer, OutputInterface $output)
    {
        $plum    = $this->getContainer()->get('madalynn.plum');
        $options = $plum->getOptions($server);

        $dryrun = '';
        if (isset($options['dry_run']) && $options['dry_run']) {
            $dryrun = '<comment>(dry run mode)</comment>';
        }

        $output->writeln(sprintf('Starting %s to <info>%s</info> %s', $deployer, $server, $dryrun));

        // Let's go!
        $plum->deploy($server, $deployer, $options);

        $output->writeln(sprintf('Successfully %s to <info>%s</info>', $deployer, $server));
    }
}

