<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

use App\Post;
use App\Image;
use App\Tag;
class PostController extends Controller
{

	public function home(){

$accountSid = $_ENV["TWILIO_ACCOUNT_SID"];
$authToken = $_ENV["TWILIO_AUTH_TOKEN"];
$mediaUrl="https://api.twilio.com/2010-04-01/Accounts/ACe766c2d9cdda628075237e977ce0808c/Messages/MM5b0b863b122ff7417f4ab1a430c04f48/Media/ME5e59e4027c675cb86e4394084b276dc9";

echo "test";
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
Storage::disk('local')->put('test2.jpg',$contents);
echo Storage::url('test2.jpg');



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
if ((!$request->input('AccountSid')) or ($request->input('AccountSid')!=$_ENV["TWILIO_ACCOUNT_SID"])){
	abort(404);
}
$replace=["Tags:","Tag:","tags:","tag:"];
if (($request->input('Body'))!=""){
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

//Storage::disk('local')->put($post->created_at.'.jpg',$contents);

Storage::disk('public')->put('test2.jpg',$contents);


//$contents=file_get_contents($request->input('MediaUrl'.$i));
//Storage::disk('local')->put('test2.jpg',$contents);
$image->image_url=Storage::url('test2.jpg');
//$image->image_url=$request->input('MediaUrl'.$i);
$image->post_id=$post->id;
$image->save();
$i++;
}


	}



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
