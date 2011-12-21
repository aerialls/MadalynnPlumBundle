# MadalynnDeployBundle

## Installation and configuration

If you use a `deps` file just add:

    [MadalynnDeployBundle]
        git=https://github.com/aerialls/MadalynnDeployBundle.git
        target=bundles/Madalynn/Bundle/DeployBundle

Or by using Git:

    git clone https://github.com/aerialls/MadalynnDeployBundle.git vendor/bundles/Madalynn/Bundle/DeployBundle

You need to add to your `autoload.php`:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Madalynn' => __DIR__.'/../vendor/bundles',
    ));

And add the MadalynnDeployBundle to your Kernel *for the dev/test environment only*.

    // app/AppKernel.php
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        // ...
        $bundles[] = new Madalynn\Bundle\DeployBundle\MadalynnDeployBundle();
    }

## Configuration example

    # DeployBundle Configuration
    madalynn_deploy:
        production:
            host: prod.mywebsite.com
            user: webuser
            dir: /var/www/mywebsite
            port: 2321 # Optional, default 22
            exclude-from: /path/to/exclude/file # Optional, default to the Resources folder
        dev:
            host: dev.mywebsite.com
            user: webuser
            dir: /var/www/mywebsite2

# Start a deploy

    php app/console project:deploy --go production