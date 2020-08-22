<?php

use Illuminate\Database\Seeder;
use App\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	$post=new Post();
	$post->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $post->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
	$post->header="Header1";
	$post->body="This is the body of the 1st post";
	$post->save();

        $post=new Post();
        $post->created_at = Carbon\Carbon::now()->subDays(2)->toDateTimeString();
        $post->updated_at = Carbon\Carbon::now()->subDays(2)->toDateTimeString();
        $post->header="Header2";
        $post->body="This is the body of the 2nd post";
        $post->save();


        $post=new Post();
        $post->created_at = Carbon\Carbon::now()->subDays(3)->toDateTimeString();
        $post->updated_at = Carbon\Carbon::now()->subDays(3)->toDateTimeString();
        $post->header="Header3";
        $post->body="This is the body of the 3rd post";
        $post->save();


        //
    }
}
