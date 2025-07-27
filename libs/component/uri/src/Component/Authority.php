<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Component;

use Boson\Contracts\Uri\Component\AuthorityInterface;
use Boson\Contracts\Uri\Component\UserInfoInterface;

final class Authority implements AuthorityInterface
{
    /**
     * @var non-empty-string
     */
    private const string HOST_PORT_DELIMITER = ':';

    /**
     * @var non-empty-string
     */
    private const string USER_INFO_DELIMITER = '@';

    /**
     * Gets the user component of the URI.
     *
     * @uses \Boson\Contracts\Uri\Component\UserInfoInterface::$user
     *
     * @var non-empty-string|null
     */
    public ?string $user {
        get => $this->userInfo?->user;
    }

    /**
     * Gets the password component of the URI.
     *
     * @uses \Boson\Contracts\Uri\Component\UserInfoInterface::$password
     *
     * @var non-empty-string|null
     */
    public ?string $password {
        get => $this->userInfo?->password;
    }

    public function __construct(
        /**
         * @var non-empty-string
         */
        public readonly string $host,
        /**
         * @var int<0, 65535>|null
         */
        public readonly ?int $port = null,
        public readonly ?UserInfoInterface $userInfo = null,
    ) {}

    public function equals(mixed $other): bool
    {
        return $other === $this
            || ($other instanceof self
                && $this->host === $other->host
                && $this->port === $other->port
                && ($other->userInfo === $this->userInfo
                    || $other->userInfo?->equals($this->userInfo) === true));
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        $result = $this->host;

        if ($this->port !== null) {
            $result .= self::HOST_PORT_DELIMITER . $this->port;
        }

        if ($this->userInfo !== null) {
            return $this->userInfo . self::USER_INFO_DELIMITER . $result;
        }

        return $result;
    }
}
