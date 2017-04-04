# SlimClickableErrors
Extended error handling for the Slim3 Framework to provide IDE-integration for errors, allowing you to jump right to the problem.

## How does it work?
If your Slim application is configured to display error details, these error handlers will replace the filename with a hyperlink to open the file in PHPStorm directly, at the right line.

## Assumptions
- If you do do not provide a "local path" (ie. the path to the code on your development machine) we will assume that your code lives on a filesystem directly accessible by PHPStorm. The path of the file as reported by PHP will be passed directly to PHPStorm.
- If you do not provide a "server path" (ie. the path on the server where the code lives), we'll assume the serverside root matching the local path is the directory above the Document Root reported by your server.

## Usage
In your index.php or bootstrap, between initializing and running the app, add the following:

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
