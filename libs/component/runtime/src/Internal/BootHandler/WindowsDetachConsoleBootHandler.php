<?php

declare(strict_types=1);

namespace Boson\Internal\BootHandler;

use Boson\Internal\Win32\Kernel32;

final class WindowsDetachConsoleBootHandler implements BootHandlerInterface
{
    public function boot(): void
    {
        // Only win32 and PHAR runtime required
        if (\PHP_OS_FAMILY !== 'Windows' || !\class_exists(\Phar::class) || \Phar::running() === '') {
            return;
        }

        $kernel32 = new Kernel32();
        $kernel32->FreeConsole();
    }
}
