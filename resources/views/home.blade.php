<!doctype html>
<html lang='en'>
<head>
    <title>Bork Bork</title>
    <meta charset='utf-8'>
<link href="{{ URL::asset('css/borkbork.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;1,200&family=Permanent+Marker&display=swap" rel="stylesheet">

</head>
<body>
<h1 id="title">bork bork</h1>

<!--    <header>

        <a href='/'><img src='/images/foobooks-logo@2x.png' id='logo' alt='Foobooks Logo'></a>
    </header>
-->

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

</body>
</html>
