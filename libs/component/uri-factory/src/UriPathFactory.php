<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory;

use Boson\Component\Uri\Component\Path;
use Boson\Contracts\Uri\Factory\UriPathFactoryInterface;

final readonly class UriPathFactory implements UriPathFactoryInterface
{
    /**
     * @var non-empty-string
     */
    private const string SEGMENT_DELIMITER = Path::PATH_SEGMENT_DELIMITER;

    public function createPathFromString(string|\Stringable $path): Path
    {
        if ($path instanceof \Stringable) {
            $path = (string) $path;
        }

        return new Path(
            segments: self::segments($path),
            isAbsolute: \str_starts_with($path, '/'),
            hasTrailingSlash: $path !== '/' && \str_ends_with($path, '/'),
        );
    }

    /**
     * @return list<non-empty-string>
     */
    private static function segments(string $path): array
    {
        $result = [];

        foreach (\explode(self::SEGMENT_DELIMITER, $path) as $segment) {
            if ($segment !== '') {
                $result[] = \urldecode($segment);
            }
        }

        return $result;
    }
}
