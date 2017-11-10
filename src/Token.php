<?php

namespace ForTheLocal\Token;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Token extends Model
{
    protected $fillable = ['name', 'token', 'data', 'expires_at'];
    public $timestamps = false;


    public function isExpired(): bool
    {
        return $this->expires_at < time();
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
     * @param int size (must be between 24 and 240)
     */
    public static function generateToken(int $size = 48): string
    {
        if ($size < 24 || $size > 240) {
            throw new InvalidArgumentException('$length must be between 24 and 240');
        }

        do {
            $token = substr(bin2hex(random_bytes($size)), 0, $size);
        } while (Token::where('token', $token)->first() != null);

        return $token;
    }

    public static function clean(): int
    {
        return Token::where('expires_at', '<', time())->delete();
    }


}

