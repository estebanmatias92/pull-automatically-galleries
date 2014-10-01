<?php

use Laracasts\TestDummy\Factory;

class GalleryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Gallery::boot();

    }

    public function testModelHasDefaultAttributes()
    {
        $gallery = new Gallery;

        $attributes = $gallery->getAttributes();

        assertThat($attributes, hasKeyValuePair('post_type', 'gallery'));
        assertThat($attributes, hasKeyValuePair('post_status', 'publish'));
        assertThat($attributes, hasKeyValuePair('post_author', 1));

    }

    public function testSaveCustomAttributesByDefault()
    {
        $date       = date('Y-m-d H:i:s');
        $attributes = [
            'post_content' => 'Dummy Content.',
            'post_title'   => 'Dummy Title',
            'post_date'    => $date
        ];
        $gallery = new Gallery($attributes);

        $gallery->save();

        assertThat($gallery->post_name, is(equalTo('dummy-title')));
        assertThat($gallery->post_date_gmt, is(equalTo($date)));
        assertThat($gallery->post_modified, is(equalTo($date)));
        assertThat($gallery->post_modified_gmt, is(equalTo($date)));

    }

    public function testFilterGalleryAttributesFromMetaFieldsWhenSaves()
    {
        $attributes = [
            'post_content'   => 'Dummy Content.',
            'post_title'     => 'Dummy Title',
            'post_date'      => date('Y-m-d H:i:s'),
            'pal_user_id'    => 1,
            'pal_gallery_id' => 1,
        ];
        $gallery = new Gallery($attributes);
        $gallery->save();

        $attributes = $gallery->getAttributes();

        assertThat($attributes, hasKey('post_content'));
        assertThat($attributes, hasKey('post_title'));
        assertThat($attributes, hasKey('post_date'));
        assertThat($attributes, not(hasKey('pal_user_id')));
        assertThat($attributes, not(hasKey('pal_gallery_id')));

    }

    public function testTestHasManyMetaRetionshipObjectIsReturned()
    {
        $gallery = Factory::create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1]);

        $relationship = $gallery->meta();

        assertThat($relationship, is(anInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany')));

    }

    public function testMetaRelationshipReturnsModels()
    {
        $gallery = Factory::create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 1, 'meta_key' => 'foo', 'meta_value' => 'bar']);

        $meta = $gallery->meta;

        assertThat($meta, is(traversableWithSize(2)));

    }

    /**
     * Ughhh! bad practice, but necessary with the current wordpress architecture.
     * With this, i can make Model::diffByKey() more easily.
     *
     * @return void
     */
    public function testMetaAttributeValuesCanBeAccessedThroughGalleryModelAttribute()
    {
        $gallery = Factory::create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 1, 'meta_key' => 'pal_gallery_id', 'meta_value' => 'foo']);

        $palGalleryId = $gallery->pal_gallery_id;
        $palUserId    = $gallery->pal_user_id;

        assertThat($palGalleryId, is(equalTo('foo')));
        assertThat($palUserId, is(equalTo(1)));
    }

    public function testCreateRelatedMetaModelsWhenCreatesNewGallery()
    {
        $attributes = [
            'post_content'   => 'Dummy content.',
            'post_title'     => 'Dummy Title 1',
            'post_date'      => date('Y-m-d H:i:s'),
            'pal_user_id'    => 1,
            'pal_gallery_id' => 1,
        ];
        $gallery = Gallery::create($attributes);

        $meta = $gallery->meta;

        assertThat($meta, is(traversableWithSize(2)));

    }

    public function testDeleteRelatedMetaModelsWhenRemovesAGallery()
    {
        $gallery = Factory::create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1]);
        $metaBeforeDelete = $gallery->meta;

        $gallery->delete();
        $metaAfterDelete = PostMeta::where('post_id', '=', 1)->get();

        assertThat($metaBeforeDelete, is(nonEmptyTraversable()));
        assertThat($metaAfterDelete, is(emptyTraversable()));

    }

    public function testGetByUsersReturnsGalleriesAsCollection()
    {
        Factory::times(2)->create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 2, 'meta_value' => 1]);
        Factory::create('User', ['id' => 1]);

        $collection = Gallery::getByUsers([1]);

        assertThat($collection, is(nonEmptyTraversable()));

    }

    public function testGetByUsersReturnsGalleriesFromSpecifiedUsers()
    {
        Factory::times(4)->create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 2, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 3, 'meta_value' => 2]);
        Factory::create('PostMeta', ['post_id' => 4, 'meta_value' => 2]);
        Factory::create('User', ['id' => 1]);
        Factory::create('User', ['id' => 2]);

        $galleriesIdsUser1 = Gallery::getByUsers([1])->lists('ID');
        $galleriesIdsUser2 = Gallery::getByUsers([2])->lists('ID');

        assertThat($galleriesIdsUser1, is(equalTo([1, 2])));
        assertThat($galleriesIdsUser2, is(equalTo([3, 4])));

    }

    public function testGetByUsersReturnsOnlyGalleryFields()
    {
        Factory::create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('User', ['id' => 1]);

        $galleries = Gallery::getByUsers([1]);

        assertThat($galleries[0], set('ID'));
        assertThat($galleries[0], set('post_author'));
        assertThat($galleries[0], set('post_content'));
        assertThat($galleries[0], set('post_title'));
        assertThat($galleries[0], set('post_date'));
        assertThat($galleries[0], set('post_status'));
        assertThat($galleries[0], set('post_name'));
        assertThat($galleries[0], notSet('post_id'));
        assertThat($galleries[0], notSet('meta_key'));
        assertThat($galleries[0], notSet('meta_value'));

    }

    public function testGetByUsersReturnsOnlySelectedFields()
    {
        Factory::create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('User', ['id' => 1]);

        $galleries = Gallery::getByUsers([1], ['ID']);

        assertThat($galleries[0], set('ID'));
        assertThat($galleries[0], notSet('post_author'));
        assertThat($galleries[0], notSet('post_content'));
        assertThat($galleries[0], notSet('post_title'));
        assertThat($galleries[0], notSet('post_date'));
        assertThat($galleries[0], notSet('post_status'));
        assertThat($galleries[0], notSet('post_name'));

    }

    public function testCreateListMakesMassiveModelInsertionIntoDb()
    {
        $gallery = Factory::create('Gallery');
        $models  = [
            new Illuminate\Support\Fluent([
                'post_content' => 'Dummy content.',
                'post_title'   => 'Dummy Title 1',
                'post_date'    => date('Y-m-d H:i:s'),
            ]),
            new Illuminate\Support\Fluent([
                'post_content' => 'Dummy content.',
                'post_title'   => 'Dummy Title 2',
                'post_date'    => date('Y-m-d H:i:s'),
            ]),
        ];
        $modelCountBefore = Gallery::all()->count();

        $gallery->createList($models);
        $modelCountAfter = Gallery::all()->count();

        assertThat($modelCountBefore, is(equalTo(1)));
        assertThat($modelCountAfter, is(equalTo(3)));

    }

    public function testCreateListReturnsCreatedModelsAsCollection()
    {
        $gallery = new Gallery;
        $models  = [
            new Illuminate\Support\Fluent([
                'post_content' => 'Dummy content.',
                'post_title'   => 'Dummy Title 1',
                'post_date'    => date('Y-m-d H:i:s'),
            ]),
            new Illuminate\Support\Fluent([
                'post_content' => 'Dummy content.',
                'post_title'   => 'Dummy Title 2',
                'post_date'    => date('Y-m-d H:i:s'),
            ]),
        ];

        $collection = $gallery->createList($models);

        assertThat($collection, is(nonEmptyTraversable()));

    }

    public function testCreateListReturnedModelsHasBeenSavedIntoDb()
    {
        $gallery = new Gallery;
        $models  = [
            new Illuminate\Support\Fluent([
                'post_content' => 'Dummy content.',
                'post_title'   => 'Dummy Title 1',
                'post_date'    => date('Y-m-d H:i:s'),
            ]),
            new Illuminate\Support\Fluent([
                'post_content' => 'Dummy content.',
                'post_title'   => 'Dummy Title 2',
                'post_date'    => date('Y-m-d H:i:s'),
            ]),
        ];

        $ids   = $gallery->createList($models)->lists('ID');
        $dbIds = Gallery::all()->lists('ID');

        assertThat($ids, is(equalTo($dbIds)));
        assertThat($ids, is(equalTo([1, 2])));

    }

    public function testDestroyByUsersRemovePostFromSpecificPalUsers()
    {
        Factory::times(2)->create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 2, 'meta_value' => 2]);
        Factory::create('User', ['id' => 1]);
        Factory::create('User', ['id' => 2]);
        $galleriesBefore = Gallery::getByUsers([1, 2]);

        Gallery::destroyByUsers([1, 2]);
        $galleriesAfter = Gallery::getByUsers([1, 2]);

        assertThat($galleriesBefore, is(nonEmptyTraversable()));
        assertThat($galleriesAfter , is(emptyTraversable()));

    }

    public function testDestroyByUsersReturnsDeleteCounter()
    {
        Factory::times(3)->create('Gallery');
        Factory::create('PostMeta', ['post_id' => 1, 'meta_value' => 1]);
        Factory::create('PostMeta', ['post_id' => 2, 'meta_value' => 2]);
        Factory::create('PostMeta', ['post_id' => 3, 'meta_value' => 3]);

        $removed = Gallery::destroyByUsers([1, 2, 3]);

        assertThat($removed, is(equalTo(3)));

    }

}
