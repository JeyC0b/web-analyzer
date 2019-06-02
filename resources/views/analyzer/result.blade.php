@if ($valid_url)
    <div><span class="title-main">Výsledek analýzy</span></div>
    <div><span class="title">Status kód:</span><span class="result">{{ $status_code }}</span></div>
    <div><span class="title">Podpora gzip:</span><span class="result">@if ($gzip_supported) Ano @else Ne @endif</span></div>
    <div><span class="title">Podpora HTTP/2.0:</span><span class="result">@if ($http2_supported) Ano @else Ne @endif</span></div>
    <div><span class="title">Podpora image/webp:</span><span class="result">@if ($webp_supported) Ano @else Ne @endif</span></div>
    <div><span class="title">Povolené indexování pro roboty - meta tag:</span><span class="result">@if ($robotsmetatag_indexing) Ano @else Ne @endif</span></div>
    <div><span class="title">Povolené indexování pro roboty - robots.txt:</span><span class="result">@if ($robotstxt_indexing) Ano @else Ne @endif</span></div>
    <div><span class="title">Povolené indexování pro roboty - X-Robots-Tag:</span><span class="result">@if ($xrobotstag_indexing) Ano @else Ne @endif</span></div>
    <div><span class="title">Vyplněný ALT tag u všech obrázků:</span><span class="result">@if ($typed_alttag) Ano @else Ne @endif</span></div>

    <div><span class="title-main">Výstup analýzy z Google Insights</span></div>
    <div><span class="title">První vykreslení obsahu:</span><span class="result"> {{ $google_insights['first_contentful_paint'] }}</span></div>
    <div><span class="title">Index rychlosti:</span><span class="result"> {{ $google_insights['speed_index'] }}</span></div>
    <div><span class="title">Doba do interaktivity:</span><span class="result"> {{ $google_insights['time_to_interactive'] }}</span></div>
    <div><span class="title">První smysluplné vykreslení:</span><span class="result"> {{ $google_insights['first_meaningful_paint'] }}</span></div>
    <div><span class="title">První nečinnost procesoru:</span><span class="result"> {{ $google_insights['first_cpu_idle'] }}</span></div>
    <div><span class="title">Max. potenciální prodleva prvního vstupu:</span><span class="result"> {{ $google_insights['estimated_input_latency'] }}</span></div>
@else
    <span>Zadejte validní URL adresu (včetně http/https)</span>
@endif