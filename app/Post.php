<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Post extends Model
{


    public function tags() {

        return $this->belongsToMany('App\Tag')->withTimestamps();

    }

	public function images(){
		return $this->hasMany('App\Image');
	} 

       public static function dates(){
		$dates=DB::select("select distinct YEAR(created_at) as year, MONTHNAME(created_at) as monthname, MONTH(created_at) as month from posts order by year DESC, month DESC");
		$dates_array=[];

$i=0;
foreach ($dates as $date){
        if(($i>0) and ($date->year==$dates_array[$i-1][0])){
                array_push($dates_array[$i-1],array($date->monthname,$date->month));

        } else {
                array_push($dates_array,array($date->year));
		array_push($dates_array[$i],array($date->monthname,$date->month));
                $i=$i+1;
        }
}

                return $dates_array;
        }

	

}
