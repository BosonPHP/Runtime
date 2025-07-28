<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory;

use Boson\Component\Uri\Component\Path;
use Boson\Contracts\Uri\Factory\PathFactoryInterface;

final readonly class PathFactory implements PathFactoryInterface
{
    /**
     * @var non-empty-string
     */
    private const string SEGMENT_DELIMITER = Path::PATH_SEGMENT_DELIMITER;

    public function createPathFromString(string $path): Path
    {
        return new Path(self::segments($path));
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
