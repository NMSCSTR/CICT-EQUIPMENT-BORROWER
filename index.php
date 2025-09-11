<?php
$title = "CICT Borrower System - Equipment Management";
include_once 'includes/header.php';
?>

<main class="flex-1">
	<!-- Hero Section with Background Image -->
	<section class="relative text-white min-h-screen flex items-center overflow-hidden">
		<div class="absolute inset-0 bg-center bg-cover scale-105" style="background-image: url('images/bg1.jpg');"></div>
		<div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-blue-800/60 to-indigo-900/70"></div>
		<div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
			<span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 text-sm tracking-wide">
				<span class="material-icons text-yellow-300 text-base">auto_awesome</span>
				Empower efficient equipment borrowing at NMSCST
			</span>
			<h1 class="mt-6 text-4xl md:text-6xl font-extrabold leading-tight">
				CICT Equipment Borrower System
			</h1>
			<p class="mt-4 md:mt-6 text-lg md:text-2xl text-blue-100 max-w-3xl mx-auto">
				Streamline requests, approvals, and returns with real‑time tracking and accountability.
			</p>
			<div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
				<a href="login.php" class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold shadow-lg shadow-black/10 hover:shadow-xl hover:-translate-y-0.5 transition">
					<span class="material-icons">login</span>
					Login to System
				</a>
				<a href="register.php" class="inline-flex items-center justify-center gap-2 border-2 border-white/80 text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-700 transition">
					<span class="material-icons">person_add</span>
					Create Account
				</a>
			</div>
			<div class="mt-14 animate-bounce-slow text-blue-100/80">
				<span class="material-icons">expand_more</span>
			</div>
		</div>

		<!-- Decorative wave divider -->
		<div class="absolute bottom-0 left-0 right-0" aria-hidden="true">
			<svg class="w-full h-24 text-gray-50" viewBox="0 0 1440 100" preserveAspectRatio="none" fill="currentColor">
				<path d="M0,64L60,80C120,96,240,128,360,144C480,160,600,160,720,144C840,128,960,96,1080,96C1200,96,1320,128,1380,144L1440,160L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path>
			</svg>
		</div>
	</section>

	<!-- Features Section -->
	<section class="bg-gray-50 py-16 md:py-24">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-12">
				<h2 class="text-2xl md:text-3xl font-bold text-gray-900">Everything you need to manage equipment</h2>
				<p class="mt-3 text-gray-600 max-w-2xl mx-auto">Simple tools for administrators, faculty, and students to keep assets moving and accounted for.</p>
			</div>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
				<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60 hover:shadow-md transition">
					<div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
						<span class="material-icons">assignment</span>
					</div>
					<h3 class="font-semibold text-gray-900">Fast borrow requests</h3>
					<p class="mt-2 text-gray-600">Submit and approve borrow requests with clear schedules and contact details.</p>
				</div>
				<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60 hover:shadow-md transition">
					<div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
						<span class="material-icons">qr_code_scanner</span>
					</div>
					<h3 class="font-semibold text-gray-900">Real‑time tracking</h3>
					<p class="mt-2 text-gray-600">Track who has what and when it’s due back to avoid conflicts.</p>
				</div>
				<div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200/60 hover:shadow-md transition">
					<div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
						<span class="material-icons">inventory_2</span>
					</div>
					<h3 class="font-semibold text-gray-900">Inventory made easy</h3>
					<p class="mt-2 text-gray-600">Organize equipment by category, condition, and availability in one place.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Stats / Callout Section -->
	<section class="bg-white py-12 md:py-16">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
				<div class="p-6 rounded-xl bg-gray-50 ring-1 ring-gray-200/70">
					<p class="text-3xl font-extrabold text-blue-700">24/7</p>
					<p class="mt-1 text-gray-600">Accessible anywhere</p>
				</div>
				<div class="p-6 rounded-xl bg-gray-50 ring-1 ring-gray-200/70">
					<p class="text-3xl font-extrabold text-blue-700">100%</p>
					<p class="mt-1 text-gray-600">Paperless process</p>
				</div>
				<div class="p-6 rounded-xl bg-gray-50 ring-1 ring-gray-200/70">
					<p class="text-3xl font-extrabold text-blue-700">Realtime</p>
					<p class="mt-1 text-gray-600">Status updates</p>
				</div>
			</div>
			<div class="mt-10 text-center">
				<a href="register.php" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition">
					<span class="material-icons">arrow_forward</span>
					Get started now
				</a>
			</div>
		</div>
	</section>
</main>

<?php include_once 'includes/footer.php'; ?>
