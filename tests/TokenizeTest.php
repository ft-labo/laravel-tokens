<?php

namespace ForThelocal\Tests;

use ForTheLocal\Tests\TestCase as TestCase;
use ForTheLocal\Token\Token;

class TokenizeTest extends TestCase
{

    private $user;
    private $post;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::create(['name' => 'name']);
        $this->post = Post::create(['title' => 'title']);

    }

    public function testHasRelation()
    {
        $this->assertTrue($this->user->tokens != null);
    }


    public function testAddToken()
    {
        $this->user->addToken("foo");
        $this->assertEquals(1, Token::count());
    }


    public function testFindToken()
    {
        $token = $this->user->addToken("foo");
        $this->assertEquals($token->token, $this->user->findToken($token->name, $token->token)->token);
        $this->assertNull($this->user->findToken($token->name, 'dummy'));
    }

    public function testFindTokenByName()
    {
        $token = $this->user->addToken("foo");
        $this->assertEquals($token->token, $this->user->findTokenByName("foo")->token);
    }

    public function testFindValidToken()
    {
        $optionsValid = ["expires_at" => strtotime('1 second', time())];
        $optionsExpired = ["expires_at" => strtotime('-1 second', time())];

        $validToken = $this->user->addToken("valid", $optionsValid);
        $expiredToken = $this->user->addToken("invalid", $optionsExpired);

        $this->assertEquals($validToken->token, $this->user->findValidToken($validToken->name, $validToken->token));
        $this->assertNull($this->user->findValidToken($expiredToken->name, $expiredToken->token));
    }

    public function testRemoveToken()
    {
        $this->user->addToken("foo");
        $this->user->addToken("bar");

        $this->assertTrue($this->user->removeToken('foo'));
        $this->assertTrue($this->user->removeToken('foo')); // removing token which does not exist returns true.
        $this->assertNull($this->user->findTokenByName("foo"));
        $this->assertNotNull($this->user->findTokenByName("bar"));
    }

    public function testAddSameNameToken()
    {
        $options1 = ["data" => 'first'];
        $options2 = ["data" => 'second'];
        $this->user->addToken("foo", $options1);
        $this->user->addToken("foo", $options2);
        $this->assertEquals("second", Token::first()->data);
    }

}
