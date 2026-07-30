<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isUserLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName = $_SESSION['FullName'] ?? '';
$userRole = $_SESSION['Role'] ?? '';
?>
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-xs">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center w-full">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="flex items-center space-x-2">
                    <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">ISpaceTech</span>
                </a>
            </div>

            <!-- Desktop Nav Items -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="index.php" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Home</a>
                <a href="blog.php" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Blogs</a>
                
                <?php if ($isUserLoggedIn): ?>
                    <?php if ($userRole === 'admin'): ?>
                        <a href="app/Dashboard/overview.php" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition">Admin Dashboard</a>
                    <?php endif; ?>
                    
                    <div class="flex items-center space-x-3 pl-4 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-sm">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </div>
                        <span class="text-sm font-medium text-slate-700"><?= htmlspecialchars($userName) ?></span>
                        <a href="logout.php" class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="flex items-center space-x-4">
                        <a href="login.php" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Login</a>
                        <a href="signup.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-xs">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <div class="flex md:hidden">
                <button type="button" id="public-menu-toggle" class="text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Dropdown Menu -->
    <div id="public-mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white">
        <div class="px-2 pt-2 pb-4 space-y-1">
            <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">Home</a>
            <a href="blog.php" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">Blogs</a>
            
            <?php if ($isUserLoggedIn): ?>
                <?php if ($userRole === 'admin'): ?>
                    <a href="app/Dashboard/overview.php" class="block px-3 py-2 rounded-md text-base font-semibold text-blue-600 hover:bg-slate-50 transition">Admin Dashboard</a>
                <?php endif; ?>
                <div class="pt-4 pb-2 border-t border-slate-100 mt-4 px-3 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-sm">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <span class="text-sm font-medium text-slate-800"><?= htmlspecialchars($userName) ?></span>
                </div>
                <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-500 hover:bg-red-50 transition">Logout</a>
            <?php else: ?>
                <div class="pt-4 border-t border-slate-100 mt-4 space-y-2">
                    <a href="login.php" class="block w-full text-center px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition">Login</a>
                    <a href="signup.php" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-base font-semibold py-2.5 rounded-lg transition shadow-xs">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById("public-menu-toggle");
    const mobileMenu = document.getElementById("public-mobile-menu");
    if (toggleBtn && mobileMenu) {
        toggleBtn.addEventListener("click", function() {
            mobileMenu.classList.toggle("hidden");
        });
    }
});
</script>