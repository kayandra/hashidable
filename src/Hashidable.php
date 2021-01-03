<?php

namespace Kayandra\Hashidable;

use Illuminate\Database\Eloquent\Model;

trait Hashidable
{
    /**
     * Finds a model by the hashid
     *
     * @param string $hash
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findByHashid(string $hash): Model {
        $static = new static();

        return $static->find($static->hashidable()->decode($hash));
    }

    /**
     * Finds a model by the hashid or fails
     *
     * @param string $hash
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findByHashidOrFail(string $hash): Model {
        $static = new static();

        return $static->findOrFail($static->hashidable()->decode($hash));
    }

    /**
     * Finds a model by the hashid or fails
     *
     * @param string $hash
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function whereHashid(string $hash): Model {
        $static = new static();

        return $static->where($static->hashidable()->decode($hash));
    }

    /**
     * Getter for the calling model to return the generated hashid
     *
     * @return string
     */
    public function getHashidAttribute($value): string {
        return $this->hashidable()->encode($this->getKey());
    }

    /**
     * Hashid Encoder-decoder
     *
     * @return \Kayandra\Hashidable\Encoder
     */
    private function hashidable(): Encoder {
        $interfaces = class_implements(get_called_class());
        $exists = array_key_exists(HashidableConfig::class, $interfaces);
        $custom = $exists ? $this->hashidableConfig() : [];
        $config = array_merge(config('hashidable'), $custom);

        return new Encoder(get_called_class(), $config);
    }

    /** @inheritDoc */
    public function getRouteKey()
    {
        return $this->hashid;
    }

    /** @inheritDoc */
    public function resolveRouteBinding($hash, $field = null)
    {
        return $this->where(
            $this->getKeyName(),
            $this->hashidable()->decode($hash)
        )->firstOrFail();
    }
}
