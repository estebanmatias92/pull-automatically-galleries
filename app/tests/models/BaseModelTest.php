<?php

class BaseModelTest extends TestCase
{
    public function testNewCollectionReturnsCustomCollection()
    {
        $model = new ModelMock;

        $collection = $model->newCollection();

        assertThat($collection, is(anInstanceOf('PullAutomaticallyGalleries\Database\Eloquent\Collection')));

    }
}

class ModelMock extends BaseModel
{

}