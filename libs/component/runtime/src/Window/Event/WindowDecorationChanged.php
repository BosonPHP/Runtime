<?php

declare(strict_types=1);

namespace Boson\Window\Event;

use Boson\Shared\Marker\AsWindowEvent;
use Boson\Window\Window;
use Boson\Window\WindowDecoration;

#[AsWindowEvent]
final class WindowDecorationChanged extends WindowEvent
{
    public function __construct(
        Window $subject,
        public readonly WindowDecoration $decoration,
        public readonly WindowDecoration $previous,
        ?int $time = null,
    ) {
        parent::__construct($subject, $time);
    }
}
