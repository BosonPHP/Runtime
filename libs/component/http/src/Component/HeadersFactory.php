<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component;

use Boson\Component\Http\Exception\InvalidHeaderNameException;
use Boson\Component\Http\Exception\InvalidHeaderValueException;
use Boson\Contracts\Http\Component\HeadersInterface;
use Boson\Contracts\Http\Factory\Component\HeadersFactoryInterface;
use Boson\Contracts\Http\Factory\MessageFactoryInterface;

/**
 * @phpstan-import-type HeadersInputType from MessageFactoryInterface
 * @phpstan-import-type HeadersListInputType from HeadersMap
 */
final readonly class HeadersFactory implements HeadersFactoryInterface
{
    public function createHeadersFromIterable(iterable $headers): HeadersInterface
    {
        if ($headers instanceof HeadersInterface) {
            return clone $headers;
        }

        return new HeadersMap($this->format($headers));
    }

    /**
     * @param HeadersInputType $headers
     * @return HeadersListInputType
     */
    protected function format(iterable $headers): array
    {
        $formatted = [];

        $index = 0;
        foreach ($headers as $name => $value) {
            if ($name === '') {
                throw InvalidHeaderNameException::becauseHeaderNameIsEmpty($index);
            }

            if (!\is_string($name)) {
                throw InvalidHeaderNameException::becauseHeaderNameIsNotString($name);
            }

            if (\is_string($value)) {
                $value = [$value];
            }

            if (!\is_array($value)) {
                throw InvalidHeaderValueException::becauseHeaderValueIsNotStringOrArray($value);
            }

            foreach ($value as $item) {
                if (!\is_string($item)) {
                    throw InvalidHeaderValueException::becauseHeaderValueIsNotString($value);
                }

                $formatted[\strtolower($name)][] = $item;
            }

            ++$index;
        }

        return $formatted;
    }
}
