@if(!isset($product))
    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->meta_title)
        <title>{{ $robotsMetaContentData?->meta_title }}</title>
        <meta name="title" content="{{ $robotsMetaContentData?->meta_title }}">
        <meta name="og:title" content="{{ $robotsMetaContentData?->meta_title }}">
        <meta name="twitter:title" content="{{ $robotsMetaContentData?->meta_title }}">
    @else
        <meta property="title" content="{{$web_config['name']->value}} "/>
        <meta property="og:title" content="{{$web_config['name']->value}} "/>
        <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    @endif

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->meta_description)
        <meta name="description" content="{{ $robotsMetaContentData?->meta_description }}">
        <meta name="og:description" content="{{ $robotsMetaContentData?->meta_description }}">
        <meta name="twitter:description" content="{{ $robotsMetaContentData?->meta_description }}">
    @else
        <meta property="description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
        <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
        <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
    @endif

    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->meta_image_full_url['path'])
        <meta property="og:image" content="{{ $robotsMetaContentData?->meta_image_full_url['path'] }}">
        <meta name="twitter:image" content="{{ $robotsMetaContentData?->meta_image_full_url['path'] }}">
        <meta name="twitter:card" content="{{ $robotsMetaContentData?->meta_image_full_url['path'] }}">
    @else
        <meta property="og:image" content="{{$web_config['web_logo']['path']}}"/>
        <meta property="twitter:image" content="{{$web_config['web_logo']['path']}}"/>
        <meta property="twitter:card" content="{{$web_config['web_logo']['path']}}"/>
    @endif

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->canonicals_url)
        <link rel="canonical" href="{{ $robotsMetaContentData?->canonicals_url }}">
    @endif

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->index != 'noindex')
        <meta name="robots" content="index">
    @endif

    @if(isset($robotsMetaContentData) && ($robotsMetaContentData?->no_follow || $robotsMetaContentData?->no_image_index || $robotsMetaContentData?->no_archive || $robotsMetaContentData?->no_snippet))
        <meta name="robots" content="{{ ($robotsMetaContentData?->no_follow ? 'nofollow' : '') . ($robotsMetaContentData?->no_image_index ? ' noimageindex' : '') . ($robotsMetaContentData?->no_archive ? ' noarchive' : '') . ($robotsMetaContentData?->no_snippet ? ' nosnippet' : '') }}">
    @endif

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->meta_max_snippet)
        <meta name="robots" content="max-snippet{{ $robotsMetaContentData?->max_snippet_value ? ': ' . $robotsMetaContentData?->max_snippet_value : '' }}">
    @endif

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->max_video_preview)
        <meta name="robots" content="max-video-preview{{ $robotsMetaContentData?->max_video_preview_value ? ': ' . $robotsMetaContentData?->max_video_preview_value : '' }}">
    @endif

    @if(isset($robotsMetaContentData) && $robotsMetaContentData?->max_image_preview)
        <meta name="robots" content="max-image-preview{{ $robotsMetaContentData?->max_image_preview_value ? ': ' . $robotsMetaContentData?->max_image_preview_value : '' }}">
    @endif
@endif
