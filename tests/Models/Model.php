<?php

namespace Kayandra\Hashidable\Tests\Models;

use Kayandra\Hashidable\Hashidable;
use Illuminate\Database\Eloquent\Model as LaravelModel;

class Model extends LaravelModel
{
    use Hashidable;
}
