<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
	$this->call(PostsTableSeeder::class);
	$this->call(ImagesTableSeeder::class);
	$this->call(TagsTableSeeder::class);
	$this->call(PostTagTableSeeder::class);

	DB::table('users')->insert([
            'name' => 'user',
            'email' => 'evan.tingle@gmail.com',
            'password' => Hash::make('HazelTheBaby2020'),
        ]);
    }
}
