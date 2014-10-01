<?php

use Laracasts\TestDummy\Factory;

class UserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        User::boot();
        PostMeta::boot();
    }

    public function testMutatorForCredentialsConvertsArrayIntoString()
    {
        $user = User::create([
            'id'          => 1,
            'host_id'     => 1,
            'realname'    => 'John Doe',
            'username'    => 'johndoe',
            'url'         => 'dummyhost.com/johndoe',
            'credentials' => [
                'token'        => 'dummy-token',
                'token_secret' => 'dummy-token-secret'
            ],
        ]);

        $credentials = $user->getAttributes()['credentials'];

        assertThat($credentials, is(stringValue()));

    }

    public function testAccessorForCredentialsConvertsStringIntoArray()
    {
        $user                   = Factory::create('User', ['id' => 1]);
        $credentialsNotAccessed = $user->getAttributes()['credentials'];

        $credentialsAccessed = $user->credentials;

        assertThat($credentialsNotAccessed, is(stringValue()));
        assertThat($credentialsAccessed, is(arrayValue()));

    }

    public function testHasManyMetaRelationshipObjectReturned()
    {
        $user = Factory::create('User', ['id' => 1]);
        Factory::times(2)->create('PostMeta', ['meta_key' => 'pal_user_id', 'meta_value' => 1]);

        $relationship = $user->meta();

        assertThat($relationship, is(anInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany')));

    }

    public function testMetaRelationshipReturnsModels()
    {
        $user = Factory::create('User', ['id' => 1]);
        Factory::times(2)->create('PostMeta', ['meta_key' => 'pal_user_id', 'meta_value' => 1]);

        $meta = $user->meta;

        assertThat($meta, is(traversableWithSize(2)));

    }

    public function testBelongsToHostRelationshipObjectReturned()
    {
        $this->assertBelongsTo('host', 'User');
    }

    public function testHostRelationshipReturnsModel()
    {
        $user = Factory::create('User', ['id' => 1]);

        $host = $user->host;

        assertThat($host, is(anInstanceOf('Host')));

    }

    public function testDeleteRelatedMetaWhenRemovesAUser()
    {
        $user = Factory::create('User', ['id' => 1]);
        Factory::times(2)->create('PostMeta', ['meta_key' => 'pal_user_id', 'meta_value' => 1]);
        $metaBeforeDelete = $user->meta;

        $user->delete();
        $metaAfterDelete = PostMeta::where('meta_key', '=', 'pal_user_id')
            ->where('meta_value', '=', $user->id)
            ->get();

        assertThat($metaBeforeDelete, is(nonEmptyTraversable()));
        assertThat($metaAfterDelete, is(emptyTraversable()));

    }

}
