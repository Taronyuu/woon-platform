@extends('layouts.app')

@section('title', $post->title . ' - Oxxen.nl')
@section('meta_description', Str::limit($post->excerpt ?? strip_tags($post->content), 160))

@section('meta')
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $post->title }} - Oxxen.nl">
<meta property="og:description" content="{{ Str::limit($post->excerpt ?? strip_tags($post->content), 200) }}">
<meta property="og:url" content="{{ url()->current() }}">
@if($post->featured_image)
<meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
@endif
<meta name="twitter:card" content="summary_large_image">
@endsection

@section('content')
<main class="container mx-auto px-4 py-12">
    <article class="max-w-3xl mx-auto">
        <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-8">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Terug naar home
        </a>

        @if($post->featured_image)
        <div class="relative h-64 md:h-96 rounded-2xl overflow-hidden mb-8">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        <header class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
            <p class="text-gray-500">{{ $post->published_at->format('d F Y') }}</p>
        </header>

        <div class="prose prose-lg max-w-none">
            {!! $post->content !!}
        </div>
    </article>
</main>
@endsection
