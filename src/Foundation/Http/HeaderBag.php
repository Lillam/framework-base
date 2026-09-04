<?php

namespace Vyui\Foundation\Http;

use Override;

class HeaderBag extends ParameterBag
{
    /**
     * @param array $parameters -> header => value (or array of values)
     */
    public function construct(array $parameters = [])
    {
        parent::__construct([]);
        
        foreach ($parameters as $header => $value) {
            $this->set($this->normalise($header), $value);
        }
    }

    /**
     * Normalise a header into the canonical form this bag stores
     * HTTP says header names are case-insensitive, and PHP hands them 
     * the server bag undescored. So "Content-Type", "CONTENT_TYPE" and 
     * "content-type" all have to resolve to the same slot. 
     * 
     * @param string $header
     * @return string
     */
    private function normalise(string $header): string 
    {
        return \strtolower(\str_replace('_', '-', \trim($header)));
    }    

    public function has(string $header): bool
    {
        return $this->get($this->normalise($header)) !== null;
    }
    
    /**
     * Override the get method to normalise the header and get it from
     * the parameter bag. 
     */
    #[Override]    
    public function get(string $header, mixed $default = null): mixed
    {
        return parent::get($this->normalise($header), $default);
    }

    /**
     * Override the set method to normalise the header and place it 
     * into the parameter bag. 
     */
    #[Override]
    public function set(string $header, mixed $value): void
    {
        parent::set($this->normalise($header), $value);
    }

    /**
     * Override the remove method, since we've normalised the header
     * into the parameter bag, we need to normalise the key to remove
     * the header from the parameter bag.
     */
    #[Override]
    public function remove(string $header): void
    {
        parent::remove($this->normalise($header));
    }
}