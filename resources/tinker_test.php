<?php

$item = \App\Models\CartItem::first();

if (!$item) {
    echo "No cart items found to test.\n";
    exit;
}

echo "Initial Qty: " . $item->quantity . "\n";

$item->quantity += 1;
$saved = $item->save();

echo "Save Result: " . ($saved ? 'true' : 'false') . "\n";

$item->refresh();

echo "New Qty: " . $item->quantity . "\n";
