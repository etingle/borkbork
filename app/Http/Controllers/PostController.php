<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
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
$array = json_decode(json_encode($request), true);

$post = new Post();
$SID="ACe766c2d9cdda628075237e977ce0808c";
if (!$request->input('AccountSid')){
	abort(404);
}
$num_images=$request->input('NumMedia');
$i=0;
while($i<$num_images){
$post->image=$request->input('MediaUrl'.$i);
}
$post->header = $request->input('From');

if (($request->input('Body'))!=""){
	$post->body = $request->input('Body');
} else {
$post->body="Body";
}
$post->save();

	#return 'Test';
	}
    //
}
