<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Twilio\Rest\Client;

use App\Post;
use App\Image;
use App\Tag;

class PostController extends Controller
{

public function home(){

if (Auth::check()){
	$posts=Post::with('tags','images')->orderBy('created_at','DESC')->get();
} else {
        $posts=Post::with('tags','images')->where('protected',NULL)->orderBy('created_at','DESC')->get();
}
$dates=Post::dates();
return view('home')
		->with([
			'posts'=>$posts,
			'dates'=>$dates
			]);	

		}	

public function searchDates($year,$month=null){

if (($month) and (Auth::check())){
$posts=Post::with('tags','images')->whereYear('created_at', $year)->whereMonth('created_at',$month)->orderBy('created_at','DESC')->get();
} elseif ($month){
$posts=Post::with('tags','images')->where('protected',NULL)->whereYear('created_at', $year)->whereMonth('created_at',$month)->orderBy('created_at','DESC')->get();
} elseif(Auth::check()){
$posts=Post::with('tags','images')->whereYear('created_at', $year)->orderBy('created_at','DESC')->get();
}
else {
$posts=Post::with('tags','images')->where('protected',NULL)->whereYear('created_at', $year)->orderBy('created_at','DESC')->get();
}

$dates=Post::dates();
return view('home')     
                ->with([
                        'posts'=>$posts,
                        'dates'=>$dates
                        ]);

                    

}

public function create(Request $request)
	{
$array =json_decode(json_encode($request), true);

$post = new Post();

if ((!$request->input('AccountSid')) or ($request->input('AccountSid')!=$_ENV["TWILIO_ACCOUNT_SID"]) or ($request->input('From')!="+12106011728")){
	abort(404);
}
$replace=["Tags:","Tag:","tags:","tag:"];
if (($request->input('Body'))!=""){
	if ((preg_match("/baby/i",$request->input('Body'))) or (preg_match("/hazel/i",$request->input('Body')))){
		$post->protected="Y";
	}

	$text=explode("\n",$request->input('Body'));	
	foreach($text as $line){
		  if (preg_match("/tags*:/i",$line)){
                        $tags=preg_replace("/tags*:/i","",$line);
                        $tags=trim($tags);
			$tags=str_replace($replace,"",$line);
			$tags=preg_split('/[\ \,]+/',$tags);
                        $remove=array_search($line,$text);
                        unset($text[$remove]);
                        }
		}
		$post->header=array_shift($text);
		$post->body=implode("%%",$text);
	
	} else {
		$post->body="";
	}	

$post->save();

if(isset($tags)){
	foreach($tags as $tag){
		$existing_tag=Tag::firstOrCreate(['name'=>$tag]);
		$post->tags()->syncWithoutDetaching($existing_tag);
		}
	}
	

$num_images=intval($request->input('NumMedia'));
$i=0;

$accountSid = $_ENV["TWILIO_ACCOUNT_SID"];
$authToken = $_ENV["TWILIO_AUTH_TOKEN"];

while ($i<$num_images){
$image=new Image();

$mediaUrl=$request->input('MediaUrl'.$i);
$image->twilio_url=$mediaUrl;

$curl = curl_init();
$options = array(
    CURLOPT_HTTPGET => true,
    CURLOPT_URL => $mediaUrl,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => "$accountSid:$authToken",
    CURLOPT_RETURNTRANSFER  => 1
);
curl_setopt_array($curl, $options);
curl_exec($curl);

$url=(curl_getinfo($curl));
$url=$url['url'];
curl_close($curl);

$contents=file_get_contents($url);
$image->aws_url=$url;
if ($post->protected=="Y"){
Storage::disk('private')->put($post->created_at->format('m-d-Y_H_i_s').'_'.$i.'.jpg',$contents);

#$image->image_url=$mediaUrl;
$image->image_url='/private/'.$post->created_at->format('m-d-Y_H_i_s').'_'.$i.'.jpg';
}
else {
Storage::disk('public')->put($post->created_at->format('m-d-Y_H_i_s').'_'.$i.'.jpg',$contents);
$image->image_url=Storage::url($post->created_at->format('m-d-Y_H_i_s').'_'.$i.'.jpg');
}

$image->post_id=$post->id;
$image->save();
$i++;

$deleteUrl=$mediaUrl.'.json';
exec("(sleep 2m; php delete.php $deleteUrl $accountSid $authToken) >/dev/null 2>&1 &");
}


	}




public function showTags($tag)
{

if (Auth::check()){
$post_id=DB::select("select posts.id from posts, tags, post_tag where posts.id=post_tag.post_id and tags.id=post_tag.tag_id and tags.name=?",[$tag]);
} else {
$post_id=DB::select("select posts.id from posts, tags, post_tag where protected is NULL and posts.id=post_tag.post_id and tags.id=post_tag.tag_id and tags.name=?",[$tag]);

}

$post_ids=[];
foreach($post_id as $i){
array_push($post_ids,$i->id);
}


$posts=Post::with('tags','images')->whereIn('id',$post_ids)->orderBy('created_at','DESC')->get();
$dates=Post::dates();
	return view('home')
		->with([
			'posts'=>$posts,
			'dates'=>$dates
			]);
}
}
