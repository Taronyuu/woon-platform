<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "Oxxen.nl",
    "url": "{{ config('app.url') }}",
    "inLanguage": "nl-NL",
    "potentialAction": {
        "@@type": "SearchAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ config('app.url') }}/zoeken?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
