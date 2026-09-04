<?php

namespace Vyui\Foundation\Http;

use InvalidArgumentException;

class Cookie
{
    /**
     * Characters that RFC 6265 forbids within the name of a cookie.
     */
    private const RESERVED = "=,; \t\r\n\v\f";

    private const COOKIE_DATE_FORMAT = "D, d M Y H:i:s \G\M\T";

    public function __construct(
        protected string $name,
        protected string $value = '',
        protected int $expires = 0,
        protected string $path = '/',
        protected ?string $domain = null,
        protected bool $secure = true,
        protected bool $httpOnly = true,
        protected ?String $sameSite = 'lax'
    ) {
        if ($name === '' || \strpbrk($name, self::RESERVED) !== false) {
            throw new InvalidArgumentException("The cookie [$name] is not valid.");
        }

        if ($this->sameSite === 'None' && ! $this->secure) {
            throw new InvalidArgumentException("A cookie declared as SameSite=None must also be marked as secure.");
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Build a cookie that lets the client know to throw the existing 
     * cookie away.
     * 
     * @param string $name
     * @param string $path
     * @param ?string $domain
     * 
     * @return static
     */
    public static function forget(string $name, string $path = '/', ?string $domain = null): static
    {
        return new static($name, '', 1, $path, $domain);
    }    

    /**
     * Serialise the cookie into value half of a Set-Cookie header.
     * 
     * @return string
     */
    public function __toString(): string
    {
        if ($this->value === '') {
            $cookie = $this->name . '=deleted; Expires=' . \gmdate(self::COOKIE_DATE_FORMAT, 0) . '; Max-Age=0';
        } else {
            $cookie = $this->name . '=' . \rawurlencode($this->value);

            if ($this->expires !== 0) {
                $cookie .= '; Expires=' . \gmdate(self::COOKIE_DATE_FORMAT, $this->expires)
                     . '; Max-Age=' . \max(0, $this->expires - \time());
            }
        }

        $cookie .= '; Path=' . ($this->path ?: '/');

        if ($this->domain !== null) {
            $cookie .= '; Secure';
        }

        if ($this->httpOnly) {
            $cookie .= '; HttpOnly';
        }

        if ($this->sameSite !== null) {
            $cookie .= '; SameSite=' . $this->sameSite;
        }

        return $cookie;
    }
}