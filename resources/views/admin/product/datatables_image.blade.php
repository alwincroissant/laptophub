@if ($product->image_url)
    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="width:52px;height:52px;object-fit:cover;">
@else
    <span class="text-muted">—</span>
@endif
