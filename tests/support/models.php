<?php
namespace ForTheLocal\Tests;

use ForTheLocal\Token\Tokenize;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Tokenize;

    public $timestamps = false;
    protected $fillable = ['name'];

    public function tokens()
    {
        return $this->morphMany('ForTheLocal\Token\Token', 'tokenizable');
    }
}

class Post extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];

    public function tokens()
    {
        return $this->morphMany('ForTheLocal\Token\Token', 'tokenizable');
    }
}
