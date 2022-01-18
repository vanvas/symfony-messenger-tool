<?php

declare(strict_types=1);

namespace Vim\MessengerTool\Message;

interface LockableInterface
{
    public function getLockKey(): string;

    public function getLockTtl(): ?float;
}
