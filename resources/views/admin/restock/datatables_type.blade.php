@if($restock->transaction_type === 'add')
    <span class="badge bg-success">➕ Add</span>
@elseif($restock->transaction_type === 'adjust')
    <span class="badge bg-warning text-dark">⚖️ Adjust</span>
@elseif($restock->transaction_type === 'remove')
    <span class="badge bg-danger">➖ Remove</span>
@else
    <span class="badge bg-secondary">{{ $restock->transaction_type }}</span>
@endif
