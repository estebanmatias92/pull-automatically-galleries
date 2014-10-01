<?php

class BaseApiModelTest extends TestCase
{
    public function testConstructSetsCache()
    {
        $model = new ApiModelStub;

        $plugins = $model->getPlugins();

        assertThat($plugins['cache'], is(objectValue()));

    }

    public function testAllReturnsArray()
    {
        $model = new ApiModelAllStub;

        $galleries = $model->all();

        assertThat($galleries, is(nonEmptyArray()));

    }

}

class ApiModelStub extends PullAutomaticallyGalleries\RemoteApi\BaseApiModel
{
    protected $clientCredentials = ['dummy-clientcredentials'];

    public function newGallery(\RestGalleries\Interfaces\GalleryAdapter $gallery = null)
    {
        return Mockery::mock('RestGalleries\\Interfaces\\GalleryAdapter');
    }

}


class ApiModelAllStub extends ApiModelStub
{
    public function newGallery(\RestGalleries\Interfaces\GalleryAdapter $gallery = null)
    {
        $collection = [
            ['foo'],
            ['bar']
        ];

        $mock = parent::newGallery();
        $mock->shouldReceive('all')
            ->once()
            ->andReturn($collection);

        return $mock;

    }

}
