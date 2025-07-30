<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode;

use Boson\Component\Http\Component\StatusCode\Category\StatusCodeCategoryImpl;
use Boson\Contracts\Http\Component\StatusCode\StatusCodeCategoryInterface;

require_once __DIR__ . '/Category/constants.php';

/**
 * Implements enum-like structure representing predefined HTTP
 * Status Code categories.
 *
 * Note: Impossible to implement via native PHP enum due to lack of support
 *       for properties: https://externals.io/message/126332
 */
final readonly class StatusCodeCategory implements StatusCodeCategoryInterface
{
    use StatusCodeCategoryImpl;

    /**
     * Informational HTTP responses
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeCategoryInterface Informational = Category\INFORMATIONAL;

    /**
     * Successful HTTP responses
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeCategoryInterface Successful = Category\SUCCESSFUL;

    /**
     * Redirection HTTP messages
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeCategoryInterface Redirection = Category\REDIRECTION;

    /**
     * Client HTTP error responses
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeCategoryInterface ClientError = Category\CLIENT_ERROR;

    /**
     * Server HTTP error responses
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeCategoryInterface ServerError = Category\SERVER_ERROR;

    /**
     * @var non-empty-list<StatusCodeCategoryInterface>
     */
    private const array CASES = [
        self::Informational,
        self::Successful,
        self::Redirection,
        self::ClientError,
        self::ServerError,
    ];

    /**
     * Return a packed {@see array} of all cases in an enumeration,
     * in order of declaration.
     *
     * @api
     *
     * @return non-empty-list<StatusCodeCategoryInterface>
     */
    public static function cases(): array
    {
        return self::CASES;
    }

    /**
     * Returns a known HTTP Status Code category by the numeric
     * code of the Status Code.
     *
     * Returns {@see null} in case of category is not known.
     *
     * @api
     */
    public static function tryFromHttpStatusCode(int $code): ?StatusCodeCategoryInterface
    {
        return match (true) {
            $code < 100 => null,
            $code < 200 => self::Informational,
            $code < 300 => self::Successful,
            $code < 400 => self::Redirection,
            $code < 500 => self::ClientError,
            $code < 600 => self::ServerError,
            default => null,
        };
    }
}
