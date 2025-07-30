<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Component;

use Boson\Component\Uri\Component\Path;
use Boson\Component\Uri\Factory\Exception\InvalidUriPathComponentException;
use Boson\Contracts\Uri\Component\PathInterface;
use Boson\Contracts\Uri\Factory\Component\UriPathFactoryInterface;

final readonly class UriPathFactory implements UriPathFactoryInterface
{
    /**
     * @var non-empty-string
     */
    private const string SEGMENT_DELIMITER = Path::PATH_SEGMENT_DELIMITER;

    public function createPathFromString(string|\Stringable $path): PathInterface
    {
        if ($path instanceof PathInterface) {
            return clone $path;
        }

        if ($path instanceof \Stringable) {
            try {
                $scalar = (string) $path;
            } catch (\Throwable $e) {
                throw InvalidUriPathComponentException::becauseStringCastingErrorOccurs($path, $e);
            }

            $path = $scalar;
        }

        if ($path === '') {
            return new Path(isAbsolute: false);
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
