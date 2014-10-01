<?php

use AspectMock\Test;

class RemoteUserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->userStub = new Illuminate\Support\Fluent([
            'id'          => 1,
            'realname'    => 'John Doe',
            'username'    => 'johndoe',
            'url'         => 'mockservice.com/johndoe',
            'credentials' => ['dummmy-credentials']
        ]);

    }

    public function testConnectGetsData()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('connect')
            ->with('')
            ->andReturn($this->userStub)
            ->getMock();
        $mock = Test::double('RemoteUser', ['newModel' => $stub]);

        $user = RemoteUser::connect('DummyHost', '');

        $mock->verifyInvoked('setHost');
        $mock->verifyInvoked('newModel');
        $mock->verifyInvoked('connect');
        $mock->verifyInvoked('modifyAttributes');
        assertThat($user, is(objectValue()));

    }

    public function testVerifyCredentialsGetsData()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('verifyCredentials')
            ->with(['dummy-credentials'])
            ->andReturn($this->userStub)
            ->getMock();
        $mock = Test::double('RemoteUser', ['newModel' => $stub]);

        $user = RemoteUser::verifyCredentials('DummyHost', ['dummy-credentials']);

        $mock->verifyInvoked('setHost');
        $mock->verifyInvoked('newModel');
        $mock->verifyInvoked('verifyCredentials');
        $mock->verifyInvoked('modifyAttributes');
        assertThat($user, is(objectValue()));

    }

    public function testSetModelProperties()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('connect')
            ->with('')
            ->andReturn($this->userStub)
            ->getMock();
        Test::double('RemoteUser', ['newModel' => $stub]);

        $user = RemoteUser::connect('DummyHost', '');

        assertThat($user, set('id'));
        assertThat($user, set('realname'));
        assertThat($user, set('username'));
        assertThat($user, set('url'));
        assertThat($user, set('credentials'));
        assertThat($user, set('host'));

    }

    public function testReturnCorrectAttributeData()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('connect')
            ->with('')
            ->andReturn($this->userStub)
            ->getMock();
        Test::double('RemoteUser', ['newModel' => $stub]);

        $user = RemoteUser::connect('DummyHost', '');

        assertThat($user->id, containsString('dummyhost_1'));
        assertThat($user->realname, is(equalTo($this->userStub['realname'])));
        assertThat($user->username, is(equalTo($this->userStub['username'])));
        assertThat($user->url, is(equalTo($this->userStub['url'])));
        assertThat($user->credentials, is(equalTo($this->userStub['credentials'])));
        assertThat($user->host, is(equalTo('DummyHost')));

    }

}
