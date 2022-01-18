<?php

declare(strict_types=1);

namespace Vim\MessengerTool\Message;

interface LockableInterface
{
    /**
     * The resource to lock
     */
    public function getLockKey(): string;

    /**
     * Maximum expected lock duration in seconds
     */
    public function getLockTtlInSeconds(): ?float;
}
