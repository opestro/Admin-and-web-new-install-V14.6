@if(isset($product) && isset($metaContentData))
    @if($metaContentData?->title)
        <meta name="title" content="{{ $metaContentData?->title }}">
        <meta name="og:title" content="{{ $metaContentData?->title }}">
        <meta name="twitter:title" content="{{ $metaContentData?->title }}">
    @else
        <meta name="title" content="{{ $product?->name }}">
        <meta name="og:title" content="{{ $product?->name }}">
        <meta name="twitter:title" content="{{ $product?->name }}">
    @endif

    @if($metaContentData?->description)
        <meta name="description" content="{!! Str::limit($metaContentData?->description, 55) !!}">
        <meta name="og:description" content="{!! Str::limit($metaContentData?->description, 55) !!}">
        <meta name="twitter:description" content="{!! Str::limit($metaContentData?->description, 55) !!}">
    @else
        <meta property="description" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="og:description" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif

    <meta property="og:url" content="{{ route('product', [$product->slug]) }}">
    <meta property="twitter:url" content="{{ route('product', [$product->slug]) }}">

    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">

    @if($product->added_by == 'seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by == 'admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif

    @if($metaContentData?->image_full_url['path'])
        <meta property="og:image" content="{{ $metaContentData?->image_full_url['path'] }}">
        <meta name="twitter:image" content="{{ $metaContentData?->image_full_url['path'] }}">
    @else
        <meta property="og:image" content="{{ $product->thumbnail_full_url['path'] }}"/>
        <meta property="twitter:card" content="{{ $product->thumbnail_full_url['path'] }}"/>
    @endif

    @if($metaContentData?->index != 'noindex')
        <meta name="robots" content="index">
    @endif

    @if($metaContentData?->no_follow || $metaContentData?->no_image_index || $metaContentData?->no_archive || $metaContentData?->no_snippet)
        <meta name="robots" content="{{ ($metaContentData?->no_follow ? 'nofollow' : '') . ($metaContentData?->no_image_index ? ' noimageindex' : '') . ($metaContentData?->no_archive ? ' noarchive' : '') . ($metaContentData?->no_snippet ? ' nosnippet' : '') }}">
    @endif

    @if($metaContentData?->meta_max_snippet)
        <meta name="robots" content="max-snippet{{ $metaContentData?->max_snippet_value ? ': ' . $metaContentData?->max_snippet_value : '' }}">
    @endif

    @if($metaContentData?->max_video_preview)
        <meta name="robots" content="max-video-preview{{ $metaContentData?->max_video_preview_value ? ': ' . $metaContentData?->max_video_preview_value : '' }}">
    @endif

    @if($metaContentData?->max_image_preview)
        <meta name="robots" content="max-image-preview{{ $metaContentData?->max_image_preview_value ? ': ' . $metaContentData?->max_image_preview_value : '' }}">
    @endif
@endif
