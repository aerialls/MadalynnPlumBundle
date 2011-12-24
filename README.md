# MadalynnDeployBundle

## Installation and configuration

If you use a `deps` file just add:

    [plum]
        git=https://github.com/aerialls/Plum.git

    [MadalynnDeployBundle]
        git=https://github.com/aerialls/MadalynnDeployBundle.git
        target=bundles/Madalynn/Bundle/DeployBundle

Or by using Git:

    git clone https://github.com/aerialls/MadalynnDeployBundle.git vendor/bundles/Madalynn/Bundle/DeployBundle
    git clone https://github.com/aerialls/Plum.git vendor/plum

You need to add to your `autoload.php`:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Madalynn' => __DIR__.'/../vendor/bundles',
        'Plum'     => __DIR__.'/../vendor/plum/src'
    ));

And add the MadalynnDeployBundle to your Kernel *for the dev/test environment only*.

    // app/AppKernel.php
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        // ...
        $bundles[] = new Madalynn\Bundle\PlumBundle\MadalynnDeployBundle();
    }

## Configuration example

    # DeployBundle Configuration
    madalynn_deploy:
        deployers:
            - Plum\Deployer\RsyncDeployer
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

# Start a deploy

    php app/console project:deploy production

You can specify a custom deployer

    php app/console project:deploy production rsync