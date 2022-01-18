<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Exception;

use Vim\MessengerTool\Message\LockableInterface;

class IsLockedException extends \Exception implements ExceptionInterface
{
    public function __construct(LockableInterface $message)
    {
        parent::__construct(
            sprintf('Message is locked by key "%s"', $message->getLockKey())
        );
    }
}
