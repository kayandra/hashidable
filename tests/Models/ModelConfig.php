<?php

namespace Kayandra\Hashidable\Tests\Models;

use Kayandra\Hashidable\Hashidable;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Kayandra\Hashidable\HashidableConfigInterface;

class ModelConfig extends LaravelModel implements HashidableConfigInterface
{
    use Hashidable;

    protected $table = 'models';

    public function hashidableConfig()
    {
        return array_merge(config('hashidable'), ['length' => 64]);
    }
}
