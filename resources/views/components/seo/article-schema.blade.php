@props(['post'])
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "headline": "{{ $post->title }}",
    "description": "{{ Str::limit($post->excerpt ?? strip_tags($post->content), 160) }}",
    @if($post->featured_image)
    "image": "{{ asset('storage/' . $post->featured_image) }}",
    @endif
    "datePublished": "{{ $post->published_at?->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {
        "@@type": "Organization",
        "name": "Oxxen.nl",
        "url": "{{ config('app.url') }}"
    },
    "publisher": {
        "@@type": "Organization",
        "name": "Oxxen.nl",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "mainEntityOfPage": {
        "@@type": "WebPage",
        "@@id": "{{ route('blog.show', $post) }}"
    },
    "inLanguage": "nl-NL"
}
</script>
