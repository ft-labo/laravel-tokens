<?php

namespace ForTheLocal\Laravel\Token;

trait Tokenizable
{

    public function tokens()
    {
        return $this->morphMany('ForTheLocal\Laravel\Token\Token', 'tokenizable');
    }

    public function findValidToken(string $name, string $token): ?Token
    {
        $token = $this->tokens()->where(['name' => $name, 'token' => $token])->first();

        if ($token == null) {
            return null;
        }

        return $token->isExpired() ? null : $token;
    }

    public function findTokenByName(string $name): ?Token
    {
        return $this->tokens()->where('name', $name)->first();
    }

    public function removeToken(string $name): bool
    {
        $token = $this->tokens()->where('name', $name)->first();

        return $token == null ? true : $token->delete();
    }

    public function addToken(string $name, array $options = []): Token
    {

        $this->removeToken($name);

        $defaultOption = [
            'length' => 24,
            'token' => null,
            'expires_at' => null,
            'data' => null
        ];

        foreach ($defaultOption as $key => $value) {
            if (!array_key_exists($key, $options)) {
                $options[$key] = $value;
            }
        }

        $options['token'] = $options['token'] ?? Token::generateToken($options['length']);


        if (is_integer($options['expires_at'])) {
            $options['expires_at'] = date("Y-m-d H:i:s", $options['expires_at']);
        }

        $attr = [
            'name' => $name,
            'token' => $options['token'],
            'expires_at' => $options['expires_at'],
            'data' => $this->encodeToJsonString($options['data'])
        ];

        return $this->tokens()->create($attr);
    }

    public static function generateToken($length = null): string
    {
        return $length == null ? Token::generateToken() : Token::generateToken($length);
    }

    public static function findByToken(string $name, string $token)
    {
        return self::whereHas('tokens', function ($query) use ($name, $token) {
            $query->where(['name' => $name, 'token' => $token]);
        })->get()->first();
    }

    public static function findByValidToken(string $name, string $token)
    {
        return self::whereHas('tokens', function ($query) use ($name, $token) {
            $query->where(['name' => $name, 'token' => $token])->where('expires_at', '>', date("Y-m-d H:i:s", time()));
        })->get()->first();
    }

    private function encodeToJsonString($data)
    {
        if (is_string($data)) {
            return $data;
        }

        return json_encode($data);
    }


}


