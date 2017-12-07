<?php

namespace ForTheLocal\Laravel\Token;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Token extends Model
{
    protected $fillable = ['name', 'token', 'data', 'expires_at'];
    public $timestamps = false;

    public function __get($name)
    {
        if ($name == "data") {
            return empty($this['data']) ? json_decode('{}') : json_decode($this['data']);
        }

        if ($name == 'created_at') {
            return new Carbon($this[$name]);
        }

        if (array_key_exists($name, $this->attributes)) {
            return $this[$name];
        }

        return null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at < date("Y-m-d H:i:s", time());
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function tokenizable()
    {
        return $this->morphTo();
    }

    public function __toString()
    {
        return $this->token;
    }

    /*
     * @param int length (must be between 24 and 240)
     */
    public static function generateToken(int $length = 48): string
    {
        if ($length < 24 || $length > 240) {
            throw new InvalidArgumentException('$length must be between 24 and 240');
        }

        do {
            $token = substr(bin2hex(random_bytes($length)), 0, $length);
        } while (Token::where('token', $token)->first() != null);

        return $token;
    }

    public static function clean(): int
    {
        return Token::where('expires_at', '<', date("Y-m-d H:i:s", time()))->delete();
    }

}

