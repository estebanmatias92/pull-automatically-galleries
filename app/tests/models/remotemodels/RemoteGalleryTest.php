<?php

use AspectMock\Test;

class RemoteGalleryTests extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->galleryStub = new Illuminate\Support\Fluent([
            'id'          => '1',
            'title'       => 'Dummy Title',
            'description' => 'Dummy Description.',
            'photos'      => ['photos'],
            'created'     => '1333699439',
            'url'         => 'http://dummyurl.com/',
            'size'        => 30,
            'user_id'     => 1,
            'thumbnail'   => 'http://dummyurl.com/gallery/photo.jpeg',
            'views'       => 600
        ]);

    }

    public function testFindReturnsModel()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->with(['foo'])
            ->once()
            ->andReturn(Mockery::self())
            ->shouldReceive('find')
            ->with('1')
            ->once()
            ->andReturn($this->galleryStub)
            ->getMock();
        $mock = Test::double('RemoteGallery', ['newModel' => $stub]);

        $gallery = RemoteGallery::find('DummyHost', ['foo'], '1');

        $mock->verifyInvoked('setHost');
        $mock->verifyInvoked('newModel');
        $mock->verifyInvoked('modifyAttributes');
        assertThat($gallery, is(objectValue()));

    }

    public function testFindNotFoundRetunsNull()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->with(['foo'])
            ->once()
            ->andReturn(Mockery::self())
            ->shouldReceive('find')
            ->with('1')
            ->once()
            ->andReturn(null)
            ->getMock();
        $mock = Test::double('RemoteGallery', ['newModel' => $stub]);

        $gallery = RemoteGallery::find('DummyHost', ['foo'], '1');

        $mock->verifyNeverInvoked('modifyAttributes');
        assertThat($gallery, is(nullValue()));

    }

    public function testSetModelProperties()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->with(['foo'])
            ->once()
            ->andReturn(Mockery::self())
            ->shouldReceive('find')
            ->with('1')
            ->once()
            ->andReturn($this->galleryStub)
            ->getMock();
        $mock = Test::double('RemoteGallery', ['newModel' => $stub]);

        $gallery = RemoteGallery::find('DummyHost', ['foo'], '1');

        assertThat($gallery, set('pal_user_id'));
        assertThat($gallery, set('pal_gallery_id'));
        assertThat($gallery, set('post_content'));
        assertThat($gallery, set('post_title'));
        assertThat($gallery, set('post_date'));
        assertThat($gallery, notSet('id'));
        assertThat($gallery, notSet('user_id'));
        assertThat($gallery, notSet('description'));
        assertThat($gallery, notSet('created'));
        assertThat($gallery, notSet('title'));

    }

    public function testReturnCorrectAttributeData()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->with(['foo'])
            ->once()
            ->andReturn(Mockery::self())
            ->shouldReceive('find')
            ->with('1')
            ->once()
            ->andReturn($this->galleryStub)
            ->getMock();
        $mock    = Test::double('RemoteGallery', ['newModel' => $stub]);
        $date    = date('Y-m-d H:i:s', $this->galleryStub['created']);
        $content = $this->galleryStub['description'];
        $title   = $this->galleryStub['title'];

        $gallery = RemoteGallery::find('DummyHost', ['foo'], '1');

        assertThat($gallery->pal_gallery_id, is(equalTo('1')));
        assertThat($gallery->pal_user_id, is(equalTo('dummyhost_1')));
        assertThat($gallery->post_content, is(equalTo($content)));
        assertThat($gallery->post_title, is(equalTo($title)));
        assertThat($gallery->post_date, is(equalTo($date)));

    }

    public function testAllReturnsModelCollection()
    {
        $galleryStubs = [$this->galleryStub, $this->galleryStub];
        $stub         = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->with(['foo'])
            ->once()
            ->andReturn(Mockery::self())
            ->shouldReceive('all')
            ->once()
            ->andReturn($galleryStubs)
            ->getMock();
        $mock = Test::double('RemoteGallery', ['newModel' => $stub]);

        $galleries = RemoteGallery::all('DummyHost', ['foo']);

        $mock->verifyInvoked('newModel');
        $mock->verifyInvoked('modifyAttributes');
        $mock->verifyInvoked('newCollection');
        assertThat($galleries, is(traversableWithSize(2)));

    }

    public function testAllReturnsEmptyCollection()
    {
        $stub = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->with(['foo'])
            ->once()
            ->andReturn(Mockery::self())
            ->shouldReceive('all')
            ->once()
            ->andReturn(null)
            ->getMock();
        $mock = Test::double('RemoteGallery', ['newModel' => $stub]);

        $galleries = RemoteGallery::all('DummyHost', ['foo']);

        $mock->verifyNeverInvoked('modifyAttributes');
        $mock->verifyInvoked('newCollection');
        assertThat($galleries, is(emptyTraversable()));

    }

    public function testGetByUsersCreatesNewGalleries()
    {
        $galleryStubs = [$this->galleryStub, $this->galleryStub];
        $stub         = Mockery::mock('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')
            ->shouldReceive('setAuth')
            ->times(2)
            ->andReturn(Mockery::self())
            ->shouldReceive('all')
            ->times(2)
            ->andReturn($galleryStubs)
            ->getMock();
        $mock      = Test::double('RemoteGallery', ['newModel' => $stub]);
        $userStubs = [
            ['host' => 'DummyHost', 'credentials' => ['foo']],
            ['host' => 'AnotherDummyHost', 'credentials' => ['bar']]
        ];

        $galleries = RemoteGallery::getByUsers($userStubs);

        $mock->verifyInvokedMultipleTimes('setHost', 2);
        $mock->verifyInvokedMultipleTimes('newModel', 2);
        $mock->verifyInvokedMultipleTimes('modifyAttributes', 4);
        $mock->verifyInvoked('newCollection');
        assertThat($galleries, is(traversableWithSize(4)));

    }

    public function testGetByUsersFail()
    {
        $mock = Test::double('RemoteGallery');

        $galleries = RemoteGallery::getByUsers([]);

        $mock->verifyNeverInvoked('newModel');
        $mock->verifyNeverInvoked('modifyAttributes');
        $mock->verifyInvoked('newCollection');
        assertThat($galleries, is(emptyTraversable()));

    }

}
