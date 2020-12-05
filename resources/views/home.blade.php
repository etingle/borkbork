<!doctype html>
<html lang='en'>
<head>
    <title>Bork Bork</title>
    <meta charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="{{ URL::asset('css/borkbork.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;1,200&family=Permanent+Marker&display=block" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta http-equiv="X-Frame-Options" content="Deny">
</head>
<body>
<div class="mobilenav">
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>
<h1 id="title"><a href="/">bork bork</a></h1>

<!--    <header>

        <a href='/'><img src='/images/foobooks-logo@2x.png' id='logo' alt='Foobooks Logo'></a>
    </header>
-->
<div id="nav">

<div class="header login">
@if (Auth::check())

<form method='POST' id='logout' action='/logout'>
    {{ csrf_field() }}
    <a href='#' onClick='document.getElementById("logout").submit();'>Logout</a>
</form>


@else

<form method='POST' action='{{ route('login') }}'>

        {{ csrf_field() }}

        <input id='email' type='email' name='email' value='evan.tingle@gmail.com' hidden>
        <input id='password' type='password' name='password' required size=10>

        <button type='submit' class='btn btn-primary'>Login</button>


    </form>
@endif
</div>

<div class="header dates">
<ul>
@foreach($dates as $date)
<li>
<span class="year"><a href='/date/{{ $date[0] }}'>{{ $date[0]}}</a></span>

@if($date[0]!=now()->year)
<ul class="dropdown">
@else
<ul>
@endif
	@foreach($date as $month)
		@if($loop->first) @continue
		@endif
<li>
<span class="month"><a href='/date/{{ $date[0] }}/{{ $month[1] }}'>{{ $month[0] }}</a></span>
</li>
@endforeach
</ul>
@endforeach
</ul>
</div>
</div>
@foreach($posts as $post)

<div class="header">
        <h1>{{ ($post->header) }}</h1>

@foreach(explode('%%',$post->body) as $body)
	        
   	<p class="body">
	{{$body}}
	</p>

@endforeach

<div class="images">
@if(!empty($post->images))
        @foreach($post->images as $image)
        <img src="{{$image->image_url}}">
        @endforeach
@endif

</div>
<p class="tags">
@foreach($post->tags as $tags)
<a href='/tag/{{$tags->name}}'>{{$tags->name}}</a>
@endforeach
</p>

<p class="date">
{{$post->created_at->format('m/d/Y')}}
</p>
</div>
@endforeach

    <footer>
    </footer>
<script>
function myFunction() {
  var x = document.getElementById("nav");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

function resize() {
  var x = document.getElementById("nav");
  if (window.innerWidth>900){
	x.style.display="block";
} else {
	x.style.display="none";
}
}

window.onresize = resize;

</script>
</body>
</html>
