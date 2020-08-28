<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Image;
class PostController extends Controller
{

	public function home(){
	
	$posts=Post::with('tags','images')->orderBy('created_at','DESC')->get();
	#$tags=$posts['tags'];
return view('home')
		->with([
			#'header'=>$header,
			#'body'=>$body,
			#'created_at'=>$created_at
			'posts'=>$posts
			#'tags'=>$tags
			]);	

		}	


public function create(Request $request)
	{
$array =json_decode(json_encode($request), true);

$post = new Post();
#$post->header=$array;
#$post->body="Body";

$SID="ACe766c2d9cdda628075237e977ce0808c";
if (!$request->input('AccountSid')){
	abort(404);
}

if (($request->input('Body'))!=""){
	$text=explode("\n",$request->input('Body'));	
	$post->header=array_shift($text);

	$post->body=implode("%%",$text);
} else {
$post->body="";
}
$post->save();

$num_images=intval($request->input('NumMedia'));
$i=0;
while ($i<$num_images){
$image=new Image();
$image->image_url=$request->input('MediaUrl'.$i);
$image->post_id=$post->id;
$image->save();
$i++;
}


#$post->images()->associate($image);
	#return 'Test';
	}
    //
}
