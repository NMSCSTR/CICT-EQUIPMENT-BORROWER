<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "CICT Borrower System"; ?></title>
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol']
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .bg-logo {
            background-image: url("../nmscstlogo.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.3;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    <header id="site-header" class="sticky top-0 z-50 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/70 transition-shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="cictlogo.png" alt="CICT Logo" class="h-12 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">CICT Borrower System</h1>
                        <p class="text-sm text-gray-600">Equipment Management System</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-2">
                    <?php
                        $links = [
                            ['href' => 'dashboard.php', 'icon' => 'home', 'label' => 'Dashboard'],
                            // ['href' => 'login.php', 'icon' => 'login', 'label' => 'Login'],
                            // ['href' => 'register.php', 'icon' => 'person_add', 'label' => 'Register'],
                        ];
                        foreach ($links as $link) {
                            $isActive = $current_page === $link['href'];
                            $baseClasses = 'px-3 py-2 rounded-lg text-sm font-medium inline-flex items-center gap-1.5 transition';
                            $activeClasses = 'text-blue-700 bg-blue-50';
                            $inactiveClasses = 'text-gray-700 hover:text-blue-700 hover:bg-gray-100';
                            $classes = $baseClasses . ' ' . ($isActive ? $activeClasses : $inactiveClasses);
                            echo '<a href="' . $link['href'] . '" class="' . $classes . '">';
                            echo '<span class="material-icons align-middle mr-0.5 text-gray-500">' . $link['icon'] . '</span>' . $link['label'];
                            echo '</a>';
                        }
                    ?>
                </nav>
                <div class="md:hidden">
                    <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 inline-flex items-center justify-center p-2 rounded-md" aria-controls="mobile-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="material-icons" id="mobile-menu-icon">menu</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 bg-white/90 backdrop-blur">
            <div class="px-4 py-3 space-y-1">
                <a href="dashboard.php" class="block px-3 py-2 rounded-md text-base <?php echo $current_page === 'dashboard.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-700'; ?>">
                    <span class="material-icons align-middle mr-2 text-gray-500">home</span>Home
                </a>
            </div>
        </div>
    </header>
    <script>
        (function() {
            var header = document.getElementById('site-header');
            var menuBtn = document.getElementById('mobile-menu-button');
            var menu = document.getElementById('mobile-menu');
            var menuIcon = document.getElementById('mobile-menu-icon');

            function onScroll() {
                if (window.scrollY > 4) {
                    header.classList.add('shadow-lg');
                } else {
                    header.classList.remove('shadow-lg');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            if (menuBtn && menu) {
                menuBtn.addEventListener('click', function() {
                    var isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        menuIcon.textContent = 'close';
                        menuBtn.setAttribute('aria-expanded', 'true');
                    } else {
                        menu.classList.add('hidden');
                        menuIcon.textContent = 'menu';
                        menuBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        })();
    </script>
