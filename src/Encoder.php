<?php

namespace Kayandra\Hashidable;

use Hashids\Hashids;

class Encoder
{
    private Hashids $encoder;

    public function __construct($salt)
    {
        $this->encoder = new Hashids(
            $this->hashSaltFromString($salt),
            config('hashidable.length')
        );
    }

    /**
     * Generates a unique hashid based on a provided integer
     *
     * @param integer $id
     * @return string
     */
    public function encode(int $id)
    {
        return $this->wrap($this->encoder->encode($id));
    }

    /**
     * Decode a model hashid to the original id.
     *
     * @param string $hash
     * @return integer
     */
    public function decode(string $hash)
    {
        $hashArray = $this->encoder->decode($this->unwrap($hash));

        return reset($hashArray);
    }

    public function hashSaltFromString(string $salt)
    {
        $input = array_fill(0, config('hashidable.length'), $salt);

        return hash('sha512', serialize($input));
    }

    private function wrap(string $hash)
    {
        $array = [$hash];
        $separator = config('hashidable.separator');

        if ($prefix = config('hashidable.prefix')) {
            array_unshift($array, $prefix, $separator);
        }

        if ($suffix = config('hashidable.suffix')) {
            array_push($array, $separator, $suffix);
        }

        return implode('', $array);
    }

    private function unwrap(string $hash)
    {
        $separator = config('hashidable.separator');

        if ($prefix = config('hashidable.prefix')) {
            $hash = ltrim($hash, $prefix . $separator);
        }

        if ($suffix = config('hashidable.suffix')) {
            $hash = rtrim($hash, $separator . $suffix);
        }

        return $hash;
    }
}
