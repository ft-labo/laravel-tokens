<?php
namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Token\Tokenizable;
use Illuminate\Database\Eloquent\Model;

trait UuidAsPrimaryKey
{
    public function getIncrementing()
    {
        return false;
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->id = \Ramsey\Uuid\Uuid::uuid4();
        });
    }
}

class User extends Model
{
    use Tokenizable, UuidAsPrimaryKey;
    public $timestamps = false;
}

class Post extends Model
{
    use Tokenizable, UuidAsPrimaryKey;
    public $timestamps = false;
}
