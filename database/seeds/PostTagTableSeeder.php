<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Tag;

class PostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	
	$i=1;
	while ($i<4){
		$post=Post::where('id','=',$i)->first();
		$j=1;
		while ($j<4){
			$tag=Tag::where('id','=',$j)->first();
			$tag->posts()->save($post);
			$j++;
			}
		$i++;
		}
        //
    }
}
