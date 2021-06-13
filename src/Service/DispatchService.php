<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Service;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class DispatchService
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(object $message, \DateTimeImmutable $delay = null): void
    {
        $stamps = [];
        $now = new \DateTimeImmutable();
        if ($delay && $delay > $now) {
            $stamps[] = new DelayStamp(($delay->getTimestamp() - $now->getTimestamp()) * 1000);
        }

        $this->messageBus->dispatch($message, $stamps);
    }
}
