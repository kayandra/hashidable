<?php

namespace Kayandra\Hashidable;

use Hashids\Hashids;

class Encoder
{
    private Hashids $encoder;

    private array $config;

    public function __construct($salt, $config = [])
    {
        $this->config = $config;
        $this->encoder = new Hashids(
            $this->modelSalt($salt),
            $this->config['length'],
            $this->config['charset']
        );
    }

    /**
     * Generates a unique hashid based on a provided integer
     *
     * @param integer $id
     * @return string
     */
    public function encode(int $id): string {
        return $this->wrap($this->encoder->encode($id));
    }

    /**
     * Decode a model hashid to the original id.
     *
     * @param string $hash
     * @return integer
     */
    public function decode(string $hash): int {
        $hashArray = $this->encoder->decode($this->unwrap($hash));

        return reset($hashArray);
    }

    /**
     * @param string $salt
     *
     * @return string
     */
    public function modelSalt(string $salt): string {
        $input = sprintf("%s\\%s", $salt, $this->config['salt'] ?? '');

        return hash('sha512', $input);
    }

    private function wrap(string $hash): string {
        $array = [$hash];
        $separator = $this->config['separator'];

        if ($prefix = $this->config['prefix']) {
            array_unshift($array, $prefix, $separator);
        }

        if ($suffix = $this->config['suffix']) {
            array_push($array, $separator, $suffix);
        }

        return implode('', $array);
    }

    private function unwrap(string $hash): string {
        $separator = $this->config['separator'];

        if ($prefix = $this->config['prefix']) {
            $hash = ltrim($hash, $prefix . $separator);
        }

        if ($suffix = $this->config['suffix']) {
            $hash = rtrim($hash, $separator . $suffix);
        }

        return $hash;
    }
}
