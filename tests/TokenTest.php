<?php

namespace ForThelocal\Tests;

use ForTheLocal\Tests\TestCase as TestCase;
use ForTheLocal\Token\Token;
use InvalidArgumentException;

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
        $user = User::create(['name' => 'name']);
        $optionsValid = ["expires_at" => strtotime('1 second', time())];
        $optionsExpired = ["expires_at" => strtotime('-1 second', time())];

        $expiredToken = $user->addToken("expired", $optionsExpired);
        $validToken = $user->addToken("not expired", $optionsValid);

        $this->assertTrue($expiredToken->isExpired());
        $this->assertFalse($validToken->isExpired());

    }

    public function testClean()
    {

        $user = User::create(['name' => 'name']);

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
