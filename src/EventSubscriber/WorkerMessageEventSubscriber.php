<?php
declare(strict_types=1);

namespace Vim\MessengerTool\EventSubscriber;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;
use Vim\MessengerTool\Exception\InvalidArgumentException;
use Vim\MessengerTool\Message\MessageInterface;
use Vim\MessengerTool\Service\LockService;

class WorkerMessageEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private LockService $lockService)
    {
    }

    #[ArrayShape([WorkerMessageReceivedEvent::class => "string[]", WorkerMessageFailedEvent::class => "string[]", WorkerMessageHandledEvent::class => "string[]"])]
    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageReceivedEvent::class => [
                'onMessageReceived'
            ],
            WorkerMessageFailedEvent::class => [
                'onMessageFailed'
            ],
            WorkerMessageHandledEvent::class => [
                'onMessageHandled'
            ],
        ];
    }

    public function onMessageReceived(WorkerMessageReceivedEvent $event): void
    {
        $this->execute($event->getEnvelope()->getMessage(), true);
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $this->execute($event->getEnvelope()->getMessage(), false);
    }

    public function onMessageHandled(WorkerMessageHandledEvent $event): void
    {
        $this->execute($event->getEnvelope()->getMessage(), false);
    }

    private function execute(object $message, bool $lock): void
    {
        if (!$message instanceof MessageInterface) {
            return;
        }

        if (!$message->getMessageId()) {
            throw new InvalidArgumentException('Message ID is null. Check the logic!');
        }

        if ($lock) {
            $this->lockService->acquire($message);
        } else {
            $this->lockService->release($message);
        }
    }
}