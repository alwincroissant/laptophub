<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Illuminate\Http\Request::create('/customer/checkout', 'GET', ['selected_cart_item_ids' => ['1']]);
$user = App\Models\User::find(2);
if ($user) {
    auth()->login($user);
} else {
    die("User 2 not found\n");
}
$controller = new App\Http\Controllers\CheckoutController();
$response = $controller->index($request);
if ($response instanceof \Illuminate\Http\RedirectResponse) {
    echo "REDIRECT: " . $response->getTargetUrl() . "\n";
    $session = $response->getSession();
    if ($session && $session->get('error')) {
        echo "SESSION ERROR: " . $session->get('error') . "\n";
    }
} else {
    echo "VIEW RENDERED\n";
}
