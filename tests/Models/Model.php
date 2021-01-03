<?php

namespace Kayandra\Hashidable\Tests\Models;

use Kayandra\Hashidable\Hashidable;
use Illuminate\Database\Eloquent\Model as ModelAlias;

class Model extends ModelAlias
{
    use Hashidable;
}
