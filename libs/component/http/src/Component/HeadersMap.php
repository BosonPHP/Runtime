<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component;

use Boson\Contracts\Http\Component\HeadersInterface;

/**
 * An implementation of immutable headers list.
 *
 * @phpstan-type HeadersListInputType iterable<non-empty-lowercase-string, list<string>>
 * @phpstan-import-type HeadersListOutputType from HeadersInterface
 *
 * @template-implements \IteratorAggregate<non-empty-lowercase-string, string>
 */
class HeadersMap implements HeadersInterface, \IteratorAggregate
{
    /**
     * @var HeadersListOutputType
     */
    protected array $lines;

    /**
     * Expects list of header values in format:
     *
     * ```
     * [
     *      'lowercase-header-name' => ['value-1', 'value-2'],
     *      'lowercase-header-name-2' => ['value-1'],
     * ]
     * ```
     *
     * @param HeadersListInputType $headers
     */
    final public function __construct(iterable $headers = [])
    {
        $this->lines = \iterator_to_array($headers);
    }

    /**
     * Creates new headers list instance from another one.
     *
     * @api
     */
    public static function createFromHeaders(HeadersInterface $headers): self
    {
        if ($headers instanceof self) {
            return clone $headers;
        }

        return new self($headers->toArray());
    }

    /**
     * According to HTTP specifications, specifically RFC-9110 and its
     * predecessors like RFC-2616 and RFC-7230, HTTP header names are
     * case-insensitive. This means that "Content-Type", "content-type",
     * and "CONTENT-TYPE" are all treated as the same header name by
     * compliant HTTP implementations.
     *
     * While the standard dictates case-insensitivity, HTTP/2 mandates
     * that header names be converted to lowercase for various reasons,
     * including performance optimization and simplification. Therefore,
     * if interacting with an HTTP/2 server, the header names will
     * consistently appear in lowercase.
     *
     * @phpstan-pure
     *
     * @param non-empty-string $name
     *
     * @return non-empty-lowercase-string
     */
    final public static function getNormalizedHeaderName(string $name): string
    {
        return \strtolower($name);
    }

    /**
     * @param non-empty-string $name
     */
    public function withAddedHeader(string $name, string $value): self
    {
        if ($name === '') {
            return $this;
        }

        $headers = $this->lines;
        $headers[self::getNormalizedHeaderName($name)][] = $value;

        return new self($headers);
    }

    /**
     * @param non-empty-string $name
     */
    public function withoutHeader(string $name): self
    {
        if ($name === '') {
            return $this;
        }

        $headers = $this->lines;
        unset($headers[self::getNormalizedHeaderName($name)]);

        return new self($headers);
    }


    public function first(string $name, ?string $default = null): ?string
    {
        $formatted = self::getNormalizedHeaderName($name);
        $lines = $this->lines;

        if (\array_key_exists($formatted, $lines)) {
            return $lines[$formatted][0] ?? $default;
        }

        return $default;
    }

    public function all(string $name): array
    {
        return $this->lines[self::getNormalizedHeaderName($name)]
            ?? [];
    }

    public function has(string $name): bool
    {
        $formatted = self::getNormalizedHeaderName($name);

        return \array_key_exists($formatted, $this->lines);
    }

    public function contains(string $name, string $value): bool
    {
        $formatted = self::getNormalizedHeaderName($name);

        return \in_array($value, $this->lines[$formatted] ?? [], true);
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->lines as $index => $values) {
            $name = (string) $index;

            foreach ($values as $value) {
                yield $name => $value;
            }
        }
    }

    public function toArray(): array
    {
        return $this->lines;
    }

    public function count(): int
    {
        return \count($this->lines);
    }
}
