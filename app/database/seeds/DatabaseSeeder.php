<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        $this->call('GalleryTableSeeder');
        $this->call('PostmetaTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('HostTableSeeder');

        $this->command->info('All tables seeded!');
	}

}
