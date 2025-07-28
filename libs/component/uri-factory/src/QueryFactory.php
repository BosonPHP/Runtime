<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory;

use Boson\Component\Uri\Component\Query;
use Boson\Contracts\Uri\Factory\QueryFactoryInterface;

final readonly class QueryFactory implements QueryFactoryInterface
{
    public function createQueryFromString(string $query): Query
    {
        return new Query(self::components($query));
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
