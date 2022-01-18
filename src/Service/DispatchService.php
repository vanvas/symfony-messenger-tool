<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Vim\MessengerTool\Exception\IsLockedException;

class DispatchService
{
    public function __construct(private MessageBusInterface $messageBus, private LoggerInterface $logger)
    {
    }

    public function dispatch(object $message, array $stamps = []): ?Envelope
    {
       try {
           return $this->messageBus->dispatch($message, $stamps);
       } catch (IsLockedException $exception) {
           $this->logger->debug('[MESSENGER] ' . $exception->getMessage());
       }

       return null;
    }

    public function dispatchNow(object $message): ?Envelope
    {
        return $this->dispatch($message);
    }

    public function dispatchAt(object $message, \DateTimeInterface $at): ?Envelope
    {
        $delayInSeconds = $at->getTimestamp() - (new \DateTimeImmutable())->getTimestamp();
        if ($delayInSeconds > 0) {
            return $this->dispatchIn($message, (float) $delayInSeconds);
        }

        return $this->dispatchNow($message);
    }

    public function dispatchIn(object $message, float $delayInSeconds): ?Envelope
    {
        return $this->dispatch($message, [
            new DelayStamp((int) ($delayInSeconds * 1000))
        ]);
    }
}
