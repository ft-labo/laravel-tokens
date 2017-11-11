<?php
namespace ForTheLocal\Tests;

use ForTheLocal\Token\Tokenizable;
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
