<?php
$redirect = $_GET['redirect'] ?? '';
$signupLink = 'signup.php' . (!empty($redirect) ? '?redirect=' . urlencode($redirect) : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In – TechPulse</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts Outfit & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, .font-display {
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

  <!-- TOP BRANDING BAR -->
  <header class="py-6 px-6 sm:px-12 flex justify-between items-center max-w-7xl w-full mx-auto">
    <a href="index.php" class="flex items-center space-x-2">
      <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">TechPulse</span>
    </a>
    <a href="index.php" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition flex items-center gap-1.5">
      &larr; Back to Home
    </a>
  </header>

  <!-- LOGIN CARD -->
  <main class="flex-1 flex items-center justify-center px-4 py-8 relative">
    <div class="absolute inset-0 opacity-5 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-400 via-indigo-600 to-slate-900 pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl border border-slate-100 shadow-xl p-8 sm:p-10 relative z-10 space-y-6">
      
      <!-- Greeting Header -->
      <div class="text-center space-y-2">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Welcome Back</h1>
        <p class="text-sm text-slate-500">Sign in to access your dashboard, articles, and discussions.</p>
      </div>

      <!-- LoginForm -->
      <form id="LoginForm" class="space-y-5">
        
        <!-- Email Field -->
        <div class="space-y-1">
          <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
              </svg>
            </span>
            <input type="email" id="email" required placeholder="you@example.com"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition placeholder-slate-400">
          </div>
        </div>

        <!-- Password Field -->
        <div class="space-y-1">
          <div class="flex justify-between items-center">
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Forgot?</a>
          </div>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </span>
            <input type="password" id="password" required placeholder="••••••••"
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition placeholder-slate-400">
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl text-sm transition shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          Sign In
        </button>

      </form>

      <!-- Footer Anchor links -->
      <div class="text-center pt-2">
        <p class="text-sm text-slate-500">
          Don't have an account? 
          <a href="<?= htmlspecialchars($signupLink) ?>" class="text-blue-600 hover:text-blue-700 font-bold transition">Sign Up</a>
        </p>
      </div>

    </div>
  </main>

  <!-- COPYRIGHT FOOTER -->
  <footer class="py-6 text-center text-xs text-slate-400">
    <p>&copy; 2026 TechPulse Blog. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/login.js"></script>
</body>
</html>