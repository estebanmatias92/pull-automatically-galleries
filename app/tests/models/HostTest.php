<?php

use Laracasts\TestDummy\Factory;

class HostTest extends TestCase
{
    public function testHasManyUsersRelationshipObjectReturned()
    {
        $this->assertHasMany('users', 'Host');
    }

    public function testUsersRelationshipReturnsModels()
    {
        $host = Factory::create('Host');
        Factory::create('User', ['id' => 1, 'host_id' => 1]);
        Factory::create('User', ['id' => 2, 'host_id' => 1]);

        $users = $host->users;

        assertThat($host->users, is(traversableWithSize(2)));

    }

}
