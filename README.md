## Installation

```shell
composer require vim/messenger-tool
```

## Configuration

`config/packages/messenger_tool.yaml`
```yaml
messenger_tool:
   lock_pool: 'app.cache.messenger_lock'
```

`config/packages/cache.yaml`
```yaml
framework:
  cache:
    pools:
      #...
      app.cache.messenger_lock:
        adapter: cache.adapter.redis
        default_lifetime: 2592000 # 30 days
```

`config/packages/messenger.yaml`
```yaml
framework:
  messenger:
    #...
    buses:
      messenger.bus.default:
        middleware:
          - Vim\MessengerTool\Middleware\MessageIdMiddleware
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
class TestMessage extends \Vim\MessengerTool\Message\AbstractMessage
{
}
```
