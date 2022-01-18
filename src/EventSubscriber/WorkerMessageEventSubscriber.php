<?php
declare(strict_types=1);

namespace Vim\MessengerTool\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Vim\MessengerTool\Message\CustomLoopableInterface;
use Vim\MessengerTool\Message\LoopableInterface;
use Vim\MessengerTool\Service\DispatchService;

class WorkerMessageEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private DispatchService $dispatchService, private LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageHandledEvent::class => [
                'onMessageHandled'
            ],
        ];
    }

    public function onMessageHandled(WorkerMessageHandledEvent $event): void
    {
        /** @var CustomLoopableInterface|LoopableInterface|object $message */
        $message = $event->getEnvelope()->getMessage();
        if (!$message instanceof LoopableInterface) {
            return;
        }

        $this->logger->debug('[MESSENGER] Loopable detected');

        $message = clone $message;
        $delayInSec = 0.01;
        if ($message instanceof CustomLoopableInterface) {
            $message->onLoopIterationHandled();
            if ($message->shouldLoopBeStopped()) {
                $this->logger->debug('[MESSENGER] FINISH LOOP');

                return;
            }

            $delayInSec = $message->getLoopDelayInSec();
        }


        $this->logger->debug('[MESSENGER] Dispatch loop message', [
            'delay' => $delayInSec,
        ]);

        $this->dispatchService->dispatchIn($message, $delayInSec);
    }
}