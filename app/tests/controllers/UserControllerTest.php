<?php

class UserControllerTest extends TestCase
{
    public function testIndexHasUsers()
    {
        User::shouldReceive('all')
            ->once()
            ->andReturn($this->collection);

        $this->call('GET', 'users');

        $this->assertViewHas('users', $this->collection);

    }

    public function testConnectAddsNewAccount()
    {
        RemoteUser::shouldReceive('connect')
            ->once()
            ->andReturn('foo');
        User::shouldReceive('create')
            ->with('foo')
            ->once();

        $this->call('POST', 'users');

        $this->assertRedirectedTo('users');
        $this->assertSessionHas('message', 'New account connected !');

    }

    public function testConnectFailsToConnectNewAccount()
    {
        RemoteUser::shouldReceive('connect')
            ->once()
            ->andThrow(new RestGalleries\Exception\AuthException('Invalid credentials'));

        $this->call('POST', 'users');

        $this->assertRedirectedTo('users');
        $this->assertSessionHas('message', 'Invalid credentials');

    }

    public function testDestroyRemovesAnUser()
    {
        User::shouldReceive('delete')
            ->with(1)
            ->once()
            ->andReturn(Mockery::mock(['delete' => true]));

        $this->call('DELETE', 'users/1');

        $this->assertRedirectedTo('users');
        $this->assertSessionHas('message', 'Account disconnected');

    }
}
