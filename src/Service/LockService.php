<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Service;

use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Vim\MessengerTool\Exception\IsLockedException;
use Vim\MessengerTool\Message\MessageInterface;

class LockService
{
    public function __construct(
        private AdapterInterface $lockStore,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * @throws IsLockedException
     * @throws InvalidArgumentException
     */
    public function acquire(MessageInterface $message): void
    {
        $lock = $this->getLock($message);
        $attempts = $lock->get() ?? 0;
        $lock->set($attempts + 1);
        $this->lockStore->save($lock);
        if ($attempts > 0) {
            if ($attempts > 1) {
                $this->logger->error('[Messenger locker] ' . $attempts . ' attempts happened. Message: ' . print_r($message, true));
            }

            throw new IsLockedException();
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function release(MessageInterface $message): void
    {
        $this->lockStore->deleteItem($this->getLock($message)->getKey());
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getLock(MessageInterface $message): CacheItem
    {
        return $this->lockStore->getItem('message.lock.' . $message->getMessageId());
    }
}
