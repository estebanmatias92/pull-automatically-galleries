<?php

use Laracasts\TestDummy\Factory;

class PostMetaTest extends TestCase
{
    public function testBelongToGalleryRelationshipObjectReturned()
    {
        $meta = Factory::create('PostMeta', ['post_id' => 1]);
        Factory::create('Gallery');

        $relationship = $meta->gallery();

        assertThat($relationship, is(anInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo')));

    }

    public function testGalleryRelationshipReturnsModel()
    {
        $meta = Factory::create('PostMeta', ['post_id' => 1]);
        Factory::create('Gallery');

        $gallery = $meta->gallery;

        assertThat($gallery, is(anInstanceof('Gallery')));

    }

    public function testBelongsToUserRelationshipObjectReturned()
    {
        $this->assertBelongsTo('user', 'PostMeta');
    }

    public function testUserRelationshipReturnsModel()
    {
        $meta = Factory::create('PostMeta', ['meta_key' => 'pal_user_id', 'meta_value' => 1]);
        Factory::create('User', ['id' => 1]);

        $user = $meta->user;

        assertThat($user, is(anInstanceof('User')));

    }

}
