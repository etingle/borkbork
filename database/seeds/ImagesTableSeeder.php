<?php

use Illuminate\Database\Seeder;
use App\Image;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	$image=new Image();
	$image->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $image->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
	$image->post_id=1;
	$image->image_url="https://s3-external-1.amazonaws.com/media.twiliocdn.com/ACe766c2d9cdda628075237e977ce0808c/fe1f3dc8a4438fe05bbc5366579ad797";
        $image->save();

        $image=new Image();
        $image->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $image->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $image->post_id=1;
        $image->image_url="https://s3-external-1.amazonaws.com/media.twiliocdn.com/ACe766c2d9cdda628075237e977ce0808c/062b5d9059e981259edd9564db50e7cf";
	$image->save();

        $image=new Image();
        $image->created_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $image->updated_at = Carbon\Carbon::now()->subDays(1)->toDateTimeString();
        $image->post_id=3;
	$image->image_url="https://s3-external-1.amazonaws.com/media.twiliocdn.com/ACe766c2d9cdda628075237e977ce0808c/9bf305532954c6df343b4c9e7a1c8408";
        $image->save();


	//
    }
}
