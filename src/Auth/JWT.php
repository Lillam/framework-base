<?php

namespace Vyui\Auth;

use Exception;
use InvalidArgumentException;

class JWT
{
    protected string $type = 'JWT';
    protected string $algorithm = 'HS256';

    protected string $hmacAlgorithm = 'sha256';
    protected string $secretToken = '7A25432A462D4A614E645267556A586E3272357538782F413F4428472B4B6250';

    protected string $header;
    protected string $payload;
    protected string $signature;

    /**
     * @param array $payload
     * @return string
     */
    public function encode(array $payload): string
    {
        return $this->setHeader([
                'typ' => $this->type,
                'alg' => $this->algorithm,
            ])
            ->setPayload($payload)
            ->setSignature()
            ->getEncoded();
    }

    /**
     * @return string
     */
    private function getEncoded(): string
    {
        return "{$this->getHeader()}.{$this->getPayload()}.{$this->getSignature()}";
    }

    /**
     * @param string $token
     * @return array
     * @throws Exception
     */
    public function decode(string $token): array
    {
        if (preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/", $token, $matches) !== 1) {
            throw new InvalidArgumentException("Invalid token format");
        }

        ['header' => $header, 'payload' => $payload, 'signature' => $signature] = $matches;

        if (! hash_equals(
            $this->prepareSignature($header, $payload),
            $this->base64urlDecode($signature)
        )) {
            throw new Exception("Signature doesn't match");
        }

        return $this->getDecoded($payload);
    }

    private function getDecoded(string $payload): array
    {
        $this->validatePayload(
            $payload = json_decode($this->base64urlDecode($payload), true)
        );

        return $payload;
    }

    /**
     * @param array $headers
     * @return $this
     */
    private function setHeader(array $headers): static
    {
        $this->header = $this->base64urlEncode(json_encode($headers));

        return $this;
    }

    /**
     * @return string
     */
    private function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param array $payload
     * @return $this
     */
    private function setPayload(array $payload): static
    {
        $this->payload = $this->base64urlEncode(json_encode($payload));

        return $this;
    }

    /**
     * @return string
     */
    private function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @return $this
     */
    private function setSignature(): static
    {
        $this->signature = $this->base64urlEncode($this->prepareSignature(
            $this->header,
            $this->payload
        ));

        return $this;
    }

    private function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return int
     */
    private function getExpiration(): int
    {
        return time() + 20;
    }

    private function validatePayload(array $payload): void
    {
        if ($payload['exp'] < time()) {
            throw new TokenExpiredException;
        }
    }

    /**
     * @param $header
     * @param $payload
     * @return string
     */
    private function prepareSignature($header, $payload): string
    {
        return hash_hmac($this->hmacAlgorithm, "{$header}.{$payload}", $this->secretToken, true);
    }

    /**
     * @param string $value
     * @return string
     */
    private function base64urlEncode(string $value): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($value));
    }

    /**
     * @param string $value
     * @return string
     */
    private function base64urlDecode(string $value): string
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $value));
    }
}