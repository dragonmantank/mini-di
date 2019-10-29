# mini-di
A super basic Dependency Injection system

The bulk of this code is used as a demonstration for a Dependency Injection article in php[architect].

This code is by no means production ready, but is meant to serve as a quick and dirty example of doing Reflection-based dependency injection along with allowing Factories for finer control over object creation. It is not performant at all.

It differs slightly from the article code in that it supports PSR-11 and (over time) small adjustments.

## Usage

Assuming we have a class, `\VideoGameService`, which has a dependency on `\PDO`, we can do something like this:

```php
$factories = [
    PDO::class => PdoFactory::class,
];
$config = [
    'db_dsn' => 'mysql://host=localhost;dbname=app',
    'db_user' => 'root',
    'db_pass' => 'secret'
];
$container = new MiniDI\Container($factories, $config);
$service = $container->get(VideoGameService::class);
$service->find('The Outer Worlds');
```

Factories are invokable objects:

```php
class PdoFactory {
    public function __invoke(MiniDI\Container $container) {
        $config = $container->getConfig();
        return new \PDO($config['db_dsn'], $config['db_user'], $config['db_pass');
    }
}
```