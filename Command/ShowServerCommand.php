<?php

/*
 * This file is part of the Madalynn package.
 *
 * (c) 2010-2011 Julien Brochet <mewt@madalynn.eu>
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

use Plum\Server\Server;

class ShowServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plum:show:server')
            ->setDescription('Shows a server')
            ->addArgument('server', InputArgument::OPTIONAL, 'The server name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $plum   = $this->getContainer()->get('madalynn.plum');
        $server = $input->getArgument('server');

        if (null === $server) {
            $servers = $plum->getServers();
        } else {
            $servers = array($server => $plum->getServer($server));
        }

        foreach ($servers as $name => $server) {
            $this->showServer($name, $server, $output);
        }
    }

    protected function showServer($name, Server $server, OutputInterface $output)
    {
        $password = '';
        if (null !== $server->getPassword()) {
            $password = str_repeat('*', strlen($server->getPassword()));
        }

        $output->writeln(sprintf('Informations for <info>%s</info> server:', $name));
        $output->writeln(sprintf('    > <comment>host</comment>:     %s', $server->getHost()));
        $output->writeln(sprintf('    > <comment>dir</comment>:      %s', $server->getDir()));
        $output->writeln(sprintf('    > <comment>user</comment>:     %s', $server->getUser()));
        $output->writeln(sprintf('    > <comment>password</comment>: %s', $password));
        $output->writeln(sprintf('    > <comment>port</comment>:     %s', $server->getPort()));

        $options = $server->getOptions();
        if (0 !== count($options)) {
            $output->writeln('    > <comment>options</comment>:');
            foreach ($options as $key => $value) {
                $output->writeln(sprintf('        > <comment>%s</comment>: %s', $key, (string)$value));
            }
        }
    }
}

