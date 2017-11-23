<title>{{ $seo_title or 'WebSite name' }}</title>
<meta name="description" content="{{ $seo_description or 'WebSite name' }}"/>
<meta name="robots" content="NOODP">

@if(env('APP_DEBUG') == true)
	<meta name="robots" content="noindex, nofollow"/>
@else
	<meta name="robots" content="index, follow"/>
@endif
