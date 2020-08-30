<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Post;
use App\Image;
use App\Tag;
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
if ((!$request->input('AccountSid')) or ($request->input('AccountSid')!=$SID)){
	abort(404);
}

if (($request->input('Body'))!=""){
	$text=explode("\n",$request->input('Body'));	
	foreach($text as $line){
		if ((strpos($line,'tag:')) or (strpos($line,'tags:'))){
			$tags=str_replace("tags:","",$line);
			$tags=str_replace("tag:","",$tags);
			$tags=preg_split('/[\ \,]+/',$tags);
			$remove=array_search($line,$text);
	unset($text[$remove]);
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



public function showTags($tag)
{

$post_id=DB::select("select posts.id from posts, tags, post_tag where posts.id=post_tag.post_id and tags.id=post_tag.tag_id and tags.name=?",[$tag]);

$post_ids=[];
foreach($post_id as $i){
array_push($post_ids,$i->id);
}


$posts=Post::with('tags','images')->whereIn('id',$post_ids)->get();

	return view('home')
		->with([
			'posts'=>$posts
			]);
}
}
