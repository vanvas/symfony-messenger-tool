<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Message;

interface MessageInterface
{
    public function getMessageId(): ?string;

    public function setMessageId(string $messageId): void;
}
