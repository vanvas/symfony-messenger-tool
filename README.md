## Installation

```shell
composer require vim/symfony-messenger-tool
```

## Configuration

`config/packages/messenger.yaml`
```yaml
framework:
  messenger:
    #...
    buses:
      messenger.bus.default:
        middleware:
          - Vim\MessengerTool\Middleware\LockableMiddleware
```

`api/config/bundles.php`
```PHP
<?php
return [
  // ...
  Vim\MessengerTool\MessengerToolBundle::class => ['all' => true],
];
```

## Example

```PHP
<?php
class TestMessage implements \Vim\MessengerTool\Message\CustomLoopableInterface, \Vim\MessengerTool\Message\LockableInterface
{
}

class TestMessage implements \Vim\MessengerTool\Message\LoopableInterface, \Vim\MessengerTool\Message\LockableInterface
{
}
```
