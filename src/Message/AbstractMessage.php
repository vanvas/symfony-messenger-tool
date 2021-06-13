<?php
declare(strict_types=1);

namespace Vim\MessengerTool\Message;

abstract class AbstractMessage implements MessageInterface
{
    private ?string $messageId = null;

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }
}
