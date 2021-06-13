<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Vim\MessengerTool\Message\MessageInterface;

class MessageIdMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        if ($message instanceof MessageInterface && !$message->getMessageId()) {
            $message->setMessageId(md5(uniqid() . rand(1000000, 99999999)));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
