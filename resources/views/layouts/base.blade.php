<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>@yield('title', 'LaptopHub')</title>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Libre+Franklin:wght@300;400;500;600&display=swap" rel="stylesheet"/>

	<style>
		:root {
			--ink: #0c0c0c;
			--red: #c0392b;
		}

		.site-footer {
			background: var(--ink);
			color: rgba(255,255,255,.55);
			padding: 3rem 0 1.5rem;
		}
		.site-footer .wordmark {
			font-family: 'Playfair Display', serif;
			font-size: 1.4rem;
			color: #fff;
			margin-bottom: .5rem;
		}
		.site-footer .wordmark span { color: var(--red); }
		.site-footer h6 {
			font-size: .7rem;
			letter-spacing: .12em;
			text-transform: uppercase;
			color: rgba(255,255,255,.4);
			margin-bottom: 1rem;
			font-weight: 600;
		}
		.site-footer a {
			display: block;
			font-size: .83rem;
			color: rgba(255,255,255,.55);
			text-decoration: none;
			margin-bottom: .45rem;
			transition: color .15s;
		}
		.site-footer a:hover { color: #fff; }
		.site-footer .divider { border-color: rgba(255,255,255,.1); margin: 2rem 0 1.5rem; }
		.site-footer .bottom { font-size: .75rem; }

		.account-menu {
			position: relative;
			display: inline-block;
		}

		.account-dropdown {
			position: absolute;
			top: calc(100% + .35rem);
			right: 0;
			min-width: 220px;
			background: #fff;
			border: 1px solid #d8d2c8;
			border-radius: 6px;
			box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
			padding: .45rem;
			display: none;
			z-index: 400;
		}

		.account-menu:hover .account-dropdown,
		.account-menu:focus-within .account-dropdown {
			display: block;
		}

		.account-link,
		.account-signout {
			display: block;
			width: 100%;
			text-align: left;
			background: transparent;
			border: none;
			color: #0c0c0c;
			text-decoration: none;
			font-size: .83rem;
			padding: .5rem .6rem;
			border-radius: 4px;
		}

		.account-link:hover,
		.account-signout:hover {
			background: #ede8df;
		}

		.account-signout-form {
			margin: 0;
		}
	</style>

	@stack('styles')
</head>
<body>
	@yield('content')

	@unless(View::hasSection('hide_footer'))
	<footer class="site-footer">
		<div class="container">
			<div class="row g-4">
				<div class="col-12 col-md-4">
					<div class="wordmark">Laptop<span>Hub</span></div>
					<p style="font-size:.82rem;line-height:1.6;max-width:260px">
						Philippines' trusted online store for laptops, components, and accessories. Fast. Verified. Affordable.
					</p>
					<div class="d-flex gap-3 mt-3" style="font-size:1.1rem">
						<a href="#" style="color:rgba(255,255,255,.5)" class="d-inline"><i class="bi bi-facebook"></i></a>
						<a href="#" style="color:rgba(255,255,255,.5)" class="d-inline"><i class="bi bi-instagram"></i></a>
						<a href="#" style="color:rgba(255,255,255,.5)" class="d-inline"><i class="bi bi-twitter-x"></i></a>
						<a href="#" style="color:rgba(255,255,255,.5)" class="d-inline"><i class="bi bi-tiktok"></i></a>
					</div>
				</div>
				<div class="col-6 col-md-2">
					<h6>Shop</h6>
					<a href="#">Laptops</a>
					<a href="#">Gaming</a>
					<a href="#">Storage</a>
					<a href="#">RAM</a>
					<a href="#">Accessories</a>
				</div>
				<div class="col-6 col-md-2">
					<h6>Account</h6>
					<a href="#login">Log In</a>
					<a href="#register">Register</a>
					<a href="#">My Orders</a>
					<a href="#">My Cart</a>
					<a href="#">Reviews</a>
				</div>
				<div class="col-6 col-md-2">
					<h6>Company</h6>
					<a href="#">About Us</a>
					<a href="#">Careers</a>
					<a href="#">Press</a>
					<a href="#">Suppliers</a>
				</div>
				<div class="col-6 col-md-2">
					<h6>Help</h6>
					<a href="#">FAQ</a>
					<a href="#">Shipping Policy</a>
					<a href="#">Returns</a>
					<a href="#">Contact Us</a>
					<a href="{{ route('legal.privacy') }}">Privacy Policy</a>
				</div>
			</div>
			<hr class="divider"/>
			<div class="bottom d-flex justify-content-between flex-wrap gap-2">
				<span>&copy; 2026 LaptopHub. All rights reserved.</span>
				<span>Made with ❤️ in the Philippines</span>
			</div>
		</div>
	</footer>
	@endunless

	@stack('scripts')
</body>
</html>
