<?php

namespace Vyui\Auth;

class Token
{
    /**
     * @var string
     */
    protected string $type = 'Token';

    /**
     * @var string
     */
    protected string $algorithm = 'HS256';

    /**
     * @var string
     */
    protected string $hmacAlgorithm = 'sha256';

    /**
     * The token in which is used to encrypt the payload.
     * TODO: | get this to be auto generated and re-generated on the fly which can be implemented into the env package
     *       | which dumps this value into your .env (APP_KEY)
     *
     * @var string
     */
    protected string $secretToken = '7A25432A462D4A614E645267556A586E3272357538782F413F4428472B4B6250';

    /**
     * @var string
     */
    protected string $header;

    /**
     * @var string
     */
    protected string $payload;

    /**
     * @var string
     */
    protected string $signature;

    /**
     * Set the secret token for the Token.
     *
     * @param string $secret
     * @return $this
     */
    public function setSecretToken(string $secret): static
    {
        $this->secretToken = $secret;

        return $this;
    }

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
     * @throws TokenInvalidException
     * @throws TokenExpiredException
     * @throws TokenSignatureMatchException
     */
    public function decode(string $token): array
    {
        if (preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/", $token, $matches) !== 1) {
            throw new TokenInvalidException("Invalid token format");
        }

        ['header' => $header, 'payload' => $payload, 'signature' => $signature] = $matches;

        if (!hash_equals(
            $this->prepareSignature($header, $payload),
            $this->base64urlDecode($signature)
        )) {
            throw new TokenSignatureMatchException("Signature doesn't match");
        }

        return $this->getDecoded($payload);
    }

    /**
     * @param string $payload
     * @return array
     * @throws TokenExpiredException
     */
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

    /**
     * @return string
     */
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

    /**
     * @param array $payload
     * @return void
     * @throws TokenExpiredException
     */
    private function validatePayload(array $payload): void
    {
        //        if ($payload['exp'] < time()) {
        //            throw new TokenExpiredException;
        //        }
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
