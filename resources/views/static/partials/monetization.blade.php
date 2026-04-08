<section class="monetization-block monetization-{{ $block['type'] }}">
    <div class="monetization-inner">

        <header class="monetization-header">
            <strong class="monetization-label">
                Recommended {{ ucfirst($block['type']) }}
            </strong>
        </header>

        <div class="monetization-body">
            <h4 class="monetization-title">
                <a href="{{ $block['url'] }}"
                   rel="sponsored nofollow noopener"
                   target="_blank">
                    {{ $block['title'] }}
                </a>
            </h4>

            <p class="monetization-cta">
                {{ $block['cta'] }}
            </p>
        </div>

        <footer class="monetization-footer">
            <span class="monetization-provider">
                by {{ $block['provider'] }}
            </span>

            @if(!empty($block['disclosure']))
                <span class="monetization-disclosure">
                    · {{ $block['disclosure'] }}
                </span>
            @endif
        </footer>

    </div>
</section>
