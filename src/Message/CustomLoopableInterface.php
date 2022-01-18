<?php

declare(strict_types=1);

namespace Vim\MessengerTool\Message;

interface CustomLoopableInterface extends LoopableInterface
{
    public function getLoopDelayInSec(): int;

    public function shouldLoopBeStopped(): bool;

    public function onLoopIterationHandled(): void;
}
