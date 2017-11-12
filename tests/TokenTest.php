<?php

namespace ForTheLocal\Test;

use ForTheLocal\Laravel\Token\Token;
use InvalidArgumentException;
use ForTheLocal\Test\TestCase as TestCase;

class TokenTest extends TestCase
{

    public function testGenerateToken()
    {
        $this->assertEquals(48, strlen(Token::generateToken()));
        $this->assertEquals(240, strlen(Token::generateToken(240)));
    }

    public function testFailGenerateTokenIfLengthIsTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        Token::generateToken(241);
    }

    public function testFailGenerateTokenIfLengthIsTooShort()
    {
        $this->expectException(InvalidArgumentException::class);
        Token::generateToken(23);
    }

    public function testIsExpired()
    {
        $user = User::create();

        $expiredToken = $user->addToken("expired", ["expires_at" => strtotime('-1 second', time())]);
        $validToken = $user->addToken("not expired",  ["expires_at" => strtotime('10 second', time())]);

        $this->assertTrue($expiredToken->isExpired());
        $this->assertFalse($validToken->isExpired());

    }

    public function testData()
    {
        $user = User::create();
        $token = $user->addToken("foo");

        $this->assertEquals(json_decode("foo"), $token->data);

        $jsonStar = '{"a":{"b":[{"c":"1"},{"c":"2"}]}}';
        $jsonArray = json_decode($jsonStar);

        $token = $user->addToken("foo", ["data" => $jsonStar]);
        $this->assertEquals($jsonArray, $token->data);

        $token = $user->addToken("foo", ["data" => $jsonArray]);
        $this->assertEquals($jsonArray, $token->data);
    }

    public function testToString()
    {
        $user = User::create();
        $token = $user->addToken("expired");

        $this->assertEquals($token->token, $token);
    }

    public function testClean()
    {

        $user = User::create();

        $names = ['foo', 'bar', 'baz'];
        $options = ["expires_at" => strtotime('-1 second', time())];
        foreach ($names as $name)
        {
            $user->addToken($name, $options);
        }

        $user->addToken("expired");

        Token::clean();

        $this->assertEquals(1, Token::count());
    }

}
