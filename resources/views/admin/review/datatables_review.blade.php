<div style="min-width:280px">
    <div style="font-weight:600">{{ $review->title ?: 'No title' }}</div>
    <div class="text-muted" style="font-size:.78rem;line-height:1.45">
        {{ \Illuminate\Support\Str::limit((string) ($review->body ?: 'No review text provided.'), 120) }}
    </div>
</div>
