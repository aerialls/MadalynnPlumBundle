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

You need to add to your `autoload.php`:

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

## Configuration example

    # PlumBundle Configuration
    madalynn_plum:
        deployers:
            - Plum\Deployer\RsyncDeployer
            - Acme\Deployer\MyCustomDeployer
        servers:
            production:
                host: prod.mywebsite.com
                user: webuser
                dir: /var/www/mywebsite
                port: 2321 # Optional, default 22
                options :
                    dry_run: true
                    rsync_exclude: /path/to/exclude
            dev:
                host: dev.mywebsite.com
                user: webuser
                dir: /var/www/mywebsite2

You can add your own deployer by using the `Plum\Deployer\DeployerInterface`

# Start a deploy

    php app/console plum:deploy production

You can specify a custom deployer via an extra parameter

    php app/console plum:deploy production rsync