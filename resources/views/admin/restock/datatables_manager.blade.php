<div class="d-flex align-items-center gap-2">
    @if(optional($restock->manager)->profile_image_url)
        <img src="{{ Storage::url($restock->manager->profile_image_url) }}" class="rounded-circle" style="width:24px;height:24px;object-fit:cover;" alt="Manager">
    @else
        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:.6rem;">
            {{ substr(optional($restock->manager)->full_name ?? '?', 0, 1) }}
        </div>
    @endif
    {{ optional($restock->manager)->full_name ?? 'Unknown' }}
</div>
