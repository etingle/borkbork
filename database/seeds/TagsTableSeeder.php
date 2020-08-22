<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tag=new Tag();
        $tag->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $tag->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $tag->name="walk";
	$tag->save();

        $tag=new Tag();
        $tag->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $tag->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $tag->name="friends";
        $tag->save();


        $tag=new Tag();
        $tag->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $tag->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $tag->name="trot";
        $tag->save();


        //
    }
}
