<?php

namespace Kayandra\Hashidable\Tests\Models;

use Kayandra\Hashidable\Hashidable;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Kayandra\Hashidable\HashidableConfig;

class ModelConfig extends LaravelModel implements HashidableConfig
{
    use Hashidable;

    protected $table = 'models';

    public function hashidableConfig(): array {
        return ['length' => 64];
    }
}
