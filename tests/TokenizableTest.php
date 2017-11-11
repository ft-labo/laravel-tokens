<?php

namespace ForThelocal\Tests;

use ForTheLocal\Tests\TestCase as TestCase;
use ForTheLocal\Token\Token;

class TokenizableTest extends TestCase
{

    private $user;
    private $post;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::create();
        $this->post = Post::create();
    }

    public function testHasRelation()
    {
        $this->assertTrue($this->user->tokens != null);
    }

    public function testAddToken()
    {
        $this->user->addToken("foo");
        $this->post->addToken("foo");
        $this->assertEquals(1, $this->user->tokens()->count());
        $this->assertEquals(1, $this->post->tokens()->count());
    }

    public function testAddTokenWithCustomToken()
    {
        $this->user->addToken("foo", ["token" => "abc123"]);
        $this->assertEquals("abc123", $this->user->findTokenByName("foo")->token);
    }


    public function testAddTokenWithLengthSpecified()
    {
        $this->user->addToken("foo", ["length" => 100]);
        $this->assertEquals(100, strlen($this->user->findTokenByName("foo")->token));
    }

    public function testFindTokenByName()
    {
        $token = $this->user->addToken("foo");
        $this->assertEquals($token->token, $this->user->findTokenByName("foo")->token);
    }

    public function testFindValidToken()
    {
        $validToken = $this->user->addToken("valid", ["expires_at" => strtotime('10 second', time())]);
        $expiredToken = $this->user->addToken("invalid", ["expires_at" => strtotime('-1 second', time())]);

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
        $this->assertEquals(json_decode("second"), Token::first()->data);
    }

    public function testGenerateToken()
    {
        $this->assertNotNull(User::generateToken());
    }

    public function testFindByToken()
    {
        $userToken = $this->user->addToken("foo");
        $postToken = $this->post->addToken("bar");

        $this->assertEquals($this->user->id, User::findByToken($userToken->name, $userToken->token)->id);
        $this->assertNull(User::findByToken($userToken->name, $postToken->token));
        $this->assertEquals($this->post->id, Post::findByToken($postToken->name, $postToken->token)->id);
        $this->assertNull(Post::findByToken($postToken->name, $userToken->token));
    }

    public function testFindByValidToken()
    {
        $userInvalidToken = $this->user->addToken("foo", ["expires_at" => strtotime('-1 second', time())]);
        $userValidToken = $this->user->addToken("bar", ["expires_at" => strtotime('10 second', time())]);

        $this->assertEquals($this->user->id, User::findByValidToken($userValidToken->name, $userValidToken->token)->id);
        $this->assertNull(Post::findByValidToken($userValidToken->name, $userValidToken->token));
        $this->assertNull(User::findByValidToken($userInvalidToken->name, $userInvalidToken->token));
        $this->assertNull(Post::findByValidToken($userInvalidToken->name, $userInvalidToken->token));
    }

}
