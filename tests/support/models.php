<?php
namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Token\Tokenizable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Tokenizable;
    public $timestamps = false;
}

class Post extends Model
{
    use Tokenizable;
    public $timestamps = false;
}
