<?php

namespace ForTheLocal\Token;

trait Tokenize
{

    public function findToken(string $name, string $token): ?Token
    {
        return $this->tokens()->where('name', $name)->where('token', $token)->first();
    }

    public function findValidToken(string $name, string $token): ?Token
    {
        $token = $this->tokens()->where('name', $name)->where('token', $token)->first();

        if ($token == null) {
            return null;
        }

        return $token->isExpired() ? null : $token;
    }

    public function isValidToken(string $name, string $token): bool
    {

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
            if (!array_key_exists($key, $options))
            {
                $options[$key] = $value;
            }
        }

        $options['token'] = $options['token'] ?? Token::generateToken($options['length']);


        $attr = [
            'name' => $name,
            'token' => $options['token'],
            'expires_at' => $options['expires_at'],
            'data' => $options['data']
        ];

        return $this->tokens()->create($attr);
    }


}


