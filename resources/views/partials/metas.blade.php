        @section('title') {{ $page['title'] }} @yield('title') @stop
        @section('description') {{ $page['description'] }} @yield('description') @stop
        @section('keywords') {{ $page['keywords'] }} @yield('keywords') @stop
        @section('facebook_admins') {{ $app->facebookadmins }} @yield('facebook_admins') @stop
        @section('facebook_page_id') {{ $app->facebookpageid }} @yield('facebook_page_id') @stop
        @section('twitter_id') {{ $app->twitterpageid }} @yield('twitter_id') @stop
        @section('author') {{ $app->author }} @yield('author') @stop
        
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport' content='width=device-width, initial-scale=1, minimum-scale=1" />
        <meta name="description" content="@yield('description')"/>
        <meta name="robots" content="All" />
        <meta name="robots" content="index, follow" />
        <meta name="keywords" content="@yield('keywords')" />
        <meta name="rating" content="General" />
        <meta name="dcterms.title" content="@yield('title')" />
        <meta name="dcterms.contributor" content="@yield('author')" />
        <meta name="dcterms.creator" content="@yield('author')" />
        <meta name="dcterms.publisher" content="@yield('author')" />
        <meta name="dcterms.description" content="@yield('description')" />
        <meta name="dcterms.rights" content="2020 - 2030" />
        <meta property="og:type" content="website" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:title" content="@yield('title')" />
        <meta property="og:url" content="{{ \Request::fullUrl() }}" />
        <meta property="og:site_name" content="@yield('author')" />
        <meta property="og:title" content="@yield('title')" />
        <meta property="og:description" content="@yield('description')" />
        <meta property="twitter:title" content="@yield('title')" />
        <meta property="twitter:description" content="@yield('description')" />
        <meta property="og:image" content="{{ URL::asset('images/favicon.png') }}"/>
        <meta property="og:image:type" content="image/png"/>
        <meta property="og:image:width" content="200"/>
        <meta property="og:image:height" content="200"/>
        <meta property="fb:admins" content="@yield('facebook_admins')" />
        <meta property="fb:page_id" content="@yield('facebook_page_id')"/>
        <meta property="twitter_id" content="@yield('twitter_id')"/>
        <meta name="_token" content="{!! csrf_token() !!}" />
        <link href="{{ \Request::fullUrl() }}" rel="canonical">
        
        
    