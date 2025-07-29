<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Component;

use Boson\Component\Uri\Component\Query;
use Boson\Contracts\Uri\Factory\Component\UriQueryFactoryInterface;

final readonly class UriQueryFactory implements UriQueryFactoryInterface
{
    public function createQueryFromString(string|\Stringable $query): Query
    {
        return new Query(self::components((string) $query));
    }

    /**
     * @return array<non-empty-string, string|array<array-key, string>>
     */
    private function components(string $query): array
    {
        \parse_str($query, $components);

        /** @var array<non-empty-string, string|array<array-key, string>> */
        return $components;
    }
}
