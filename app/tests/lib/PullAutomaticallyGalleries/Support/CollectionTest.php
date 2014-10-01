<?php namespace PullAutomaticallyGalleries\Tests\PullAutomaticallyGalleries\Support;

use Faker\Factory as Faker;
use PullAutomaticallyGalleries\Support\Collection;

class CollectionTest extends \TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->collection = new Collection([
            [
                'id'   => 3,
                'name' => 'John Doe',
            ],
            [
                'id'   => 4,
                'name' => 'Taylor Otwell',
            ]
        ]);

    }

    public function testDiffByKeyReturnsTheDifference()
    {
        $collectionIds     = $this->collection->lists('id');
        $collectionStub    = ModelStub::all();
        $collectionStubIds = $collectionStub->lists('id');

        $diff    = $this->collection->diffByKey($collectionStub);
        $diffIds = $diff->lists('id');

        assertThat($collectionIds, is(equalTo([3, 4])));
        assertThat($collectionStubIds, is(equalTo([1, 2, 3])));
        assertThat($diffIds, is(equalTo([4])));

    }

    public function testDiffByKeyWithNonDefaultKey()
    {
        $collectionStub = ModelStub::all();

        $diff      = $this->collection->diffByKey($collectionStub, 'name');
        $diffNames = $diff->lists('name');

        assertThat($diffNames, is(equalTo(['Taylor Otwell'])));

    }

}

class ModelStub
{
    public static function all()
    {
        $faker = Faker::create();

        $models[] = [
            'id'   => 1,
            'name' => 'John Doe',
        ];

        foreach (range(2, 3) as $value) {
            $models[] = [
                'id'   => $value,
                'name' => $faker->name,
            ];
        }

        return new \Illuminate\Support\Collection($models);

    }

}
