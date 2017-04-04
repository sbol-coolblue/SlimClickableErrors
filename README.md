# SlimClickableErrors
Extended error handling for the Slim3 Framework to provide IDE-integration for errors, allowing you to jump right to the problem.

## How does it work?
If your Slim application is configured to display full errors, these error handlers will replace the filename with a hyperlink to open the file in PHPStorm directly.

While developing our apps often run on a different (virtualized) environment (`/var/www/html/public/index.php` on a Vagrant VM, for example), the error handlers allow you to specify which directories on your development environment the links should be pointing to (such as `/Users/sanderbol/Projects/my-project/public/index.php` on my Macbook)

## Usage
In your index.php file, after initializing the app, add the following:

```php
$c = $app->getContainer();
$c['errorHandler'] = function(Interop\Container\ContainerInterface $container) {
    return new \SanderBol\ClickableError\ErrorHandler(
        $container->get('settings')['displayErrorDetails'],        
        '/Users/sanderbol/Projects/my-project', // Directory on your host OS. Optional.
        '/var/www/html/' // Directory on the virtualized filesystem your app runs on. Optional.
    );
};

$c['phpErrorHandler'] = function(Interop\Container\ContainerInterface $container) {
    return new \SanderBol\ClickableError\PhpErrorHandler(
        $container->get('settings')['displayErrorDetails'],        
        '/Users/sanderbol/Projects/my-project', // Directory on your host OS. Optional.
        '/var/www/html/' // Directory on the virtualized filesystem your app runs on. Optional.
    );
};
```