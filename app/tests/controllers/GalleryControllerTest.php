<?php

use AspectMock\Test;

class GalleryControllerTest extends TestCase
{
    public function testUpdateList()
    {
        $userStubs           = [['id' => 1], ['id' => 2]];
        $localCollectionStub = Test::double('PullAutomaticallyGalleries\Database\Eloquent\Collection', [
            'diffByKey' => &$localCollectionStub,
            'isEmpty'   => false,
            'lists'     => [1]
        ])->make();
        $remoteCollectionStub = Test::double('PullAutomaticallyGalleries\Support\Collection', [
            'diffByKey' => &$remoteCollectionStub,
            'isEmpty'   => false
        ])->make();
        $createdModelsStub = Test::double('PullAutomaticallyGalleries\Database\Eloquent\Collection', ['count' => 2])->make();
        $galleryMock = Gallery::shouldReceive('getByUsers')
            ->with([1, 2])
            ->once()
            ->andReturn($localCollectionStub)
            ->getMock();
        RemoteGallery::shouldReceive('getByUsers')
            ->with($userStubs)
            ->once()
            ->andReturn($remoteCollectionStub);
        $galleryMock->shouldReceive('destroy')
            ->with([1])
            ->once()
            ->andReturn(1);
        $galleryMock->shouldReceive('createList')
            ->with($remoteCollectionStub)
            ->once()
            ->andReturn($createdModelsStub);

        $this->call('POST', 'galleries', ['users' => $userStubs]);

        $this->assertRedirectedTo('users');
        $this->assertSessionHas('message', 'Has been removed 1 galleries and created 2 galleries');

    }

    public function testUpdateListHasNothingToUpdate()
    {
        $userStubs           = [['id' => 1], ['id' => 2]];
        $localCollectionStub = Test::double('PullAutomaticallyGalleries\Database\Eloquent\Collection', [
            'diffByKey' => &$localCollectionStub,
            'isEmpty'   => true
        ])->make();
        $remoteCollectionStub = Test::double('PullAutomaticallyGalleries\Support\Collection', [
            'diffByKey' => &$remoteCollectionStub,
            'isEmpty'   => true
        ])->make();
        Gallery::shouldReceive('getByUsers')
            ->with([1, 2])
            ->once()
            ->andReturn($localCollectionStub)
            ->getMock();
        RemoteGallery::shouldReceive('getByUsers')
            ->with($userStubs)
            ->once()
            ->andReturn($remoteCollectionStub);

        $this->call('POST', 'galleries', ['users' => $userStubs ]);

        $this->assertRedirectedTo('users');
        $this->assertSessionHas('message', 'Has been removed 0 galleries and created 0 galleries');

    }

    public function testDestroyList()
    {
        $userStubs = [['id' => 1], ['id' => 2]];
        Gallery::shouldReceive('destroyByUsers')
            ->with([1, 2])
            ->once()
            ->andReturn(4);

        $this->call('DELETE', 'galleries', ['users' => $userStubs]);

        $this->assertRedirectedTo('users');
        $this->assertSessionHas('message', 'Has been removed 4 galleries');

    }
}
