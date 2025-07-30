<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Factory\Component;

use Boson\Component\Uri\Component\Query;
use Boson\Component\Uri\Factory\Exception\InvalidUriQueryComponentException;
use Boson\Contracts\Uri\Component\QueryInterface;
use Boson\Contracts\Uri\Factory\Component\UriQueryFactoryInterface;

final readonly class UriQueryFactory implements UriQueryFactoryInterface
{
    public function createQueryFromString(string|\Stringable $query): QueryInterface
    {
        if ($query instanceof QueryInterface) {
            return clone $query;
        }

        if ($query instanceof \Stringable) {
            try {
                $scalar = (string) $query;
            /** @phpstan-ignore-next-line : PHPStan false-positive, this is not dead catch */
            } catch (\Throwable $e) {
                throw InvalidUriQueryComponentException::becauseStringCastingErrorOccurs($query, $e);
            }

            $query = $scalar;
        }

        if ($query === '') {
            return new Query();
        }

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
