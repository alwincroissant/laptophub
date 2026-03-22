@if($item->image_url)
    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-thumbnail" style="width:48px;height:48px;object-fit:cover;">
@else
    <span class="text-muted">-</span>
@endif
