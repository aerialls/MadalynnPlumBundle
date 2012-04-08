# MadalynPlumBundle

The PlumBundle is a deploy bundle using several deployers.

## Installation and configuration

If you use a `deps` file just add:

    [plum]
        git=https://github.com/aerialls/Plum.git

    [MadalynnPlumBundle]
        git=https://github.com/aerialls/MadalynnPlumBundle.git
        target=bundles/Madalynn/Bundle/PlumBundle

Or by using Git:

    git clone https://github.com/aerialls/MadalynnPlumBundle.git vendor/bundles/Madalynn/Bundle/PlumBundle
    git clone https://github.com/aerialls/Plum.git vendor/plum

Or by Composer

    "require": {
        "madalynn/plum-bundle": "dev-master"
    },

You need to add to your `autoload.php`: (not necessary if you're using Composer)

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Madalynn' => __DIR__.'/../vendor/bundles',
        'Plum'     => __DIR__.'/../vendor/plum/src'
    ));

And add the MadalynnPlumBundle to your Kernel *for the dev/test environment only*.

    // app/AppKernel.php
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        // ...
        $bundles[] = new Madalynn\Bundle\PlumBundle\MadalynnPlumBundle();
    }

## Deployers

By default, Plum provides 2 providers : `Plum\Deployer\RsyncDeployer` and
`Plum\Deployer\SshDeployer`. Here are the options for each :

* `Plum\Deployer\RsyncDeployer`
 * `dry_run`
 * `rsync_exclude`
* `Plum\Deployer\SshDeployer`
 * `dry_run`
 * `commands`

You can add your own deployer by using the `Plum\Deployer\DeployerInterface`
interface.

## Configuration example

    # app/config/config_dev.yml
    madalynn_plum:
        options: # Global options
            dry_run: false
            rsync_exclude: "%kernel.root_dir%/config/rsync_exclude.txt"
            commands:
                - 'php app/console cache:clear --env=prod --no-debug'
        deployers:
            - Plum\Deployer\RsyncDeployer
            - Plum\Deployer\SshDeployer
            - Acme\Deployer\MyCustomDeployer
        servers_file: "%kernel.root_dir%/config/deployment.yml"

The aim here is to seperate the servers configration from the rest. It is then
possible to add the `deployment.yml` to the exclude file.

    # app/config/deployment.yml
    servers:
        production:
            host: prod.mywebsite.com
            user: webuser
            dir: /var/www/mywebsite
            port: 2321 # Optional, default 22
            password: myPaSsWoRd # Just for the SSH deployer
            options: # Server options, override the globals
                dry_run: %kernel.debug%
        dev:
            host: dev.mywebsite.com
            user: webuser
            dir: /var/www/mywebsite2

# Start a deploy

    php app/console plum:deploy production --no-debug

You can specify a custom deployer via an extra parameter

    php app/console plum:deploy production rsync,ssh