<?php


class BaseRemoteModelTest extends TestCase
{
    public function testNewCollectionReturnsCollection()
    {
        $model = new RemoteModelStub;

        $collection = $model->newCollection();

        assertThat($collection, is(anInstanceOf('PullAutomaticallyGalleries\Support\Collection')));

    }

    public function testSetHostToCreateNewGallery()
    {
        $model = new RemoteModelStub;

        $model->setHost('Flickr');
        $gallery = $model->newModel();

        assertThat($gallery, is(anInstanceOf('PullAutomaticallyGalleries\\RemoteApi\\RemoteApiModelInterface')));

    }

}

class RemoteModelStub extends BaseRemoteModel
{

}