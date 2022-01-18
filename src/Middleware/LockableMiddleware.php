<?php

declare(strict_types=1);

namespace Vim\MessengerTool\Middleware;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Vim\MessengerTool\Exception\IsLockedException;
use Vim\MessengerTool\Message\LockableInterface;

class LockableMiddleware implements MiddlewareInterface
{
    public function __construct(private LockFactory $lockFactory)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if (!$message instanceof LockableInterface) {
            return $stack->next()->handle($envelope, $stack);
        }

        $lock = $this->lockFactory->createLock('messenger.lock.' . $message->getLockKey(), 86400);
        if ($lock->acquire()) {
            return $stack->next()->handle($envelope, $stack);
        }

        throw new IsLockedException($message);
    }
}
