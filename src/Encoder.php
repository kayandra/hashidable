<?php

namespace Kayandra\Hashidable;

use Hashids\Hashids;

class Encoder
{
    private $encoder;

    public function __construct($salt, $length = 16)
    {
        $this->encoder = new Hashids($salt, $length);
    }

    /**
     * Generates a unique hashid based on a provided integer
     *
     * @param integer $id
     * @return string
     */
    public function encode(int $id)
    {
        return $this->encoder->encode($id);
    }

    /**
     * Decode a model hashid to the original id.
     *
     * @param string $hash
     * @return integer
     */
    public function decode(string $hash)
    {
        $hashArray = $this->encoder->decode($hash);

        return reset($hashArray);
    }
}
