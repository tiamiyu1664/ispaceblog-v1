<header class="bg-white shadow px-6 py-4 flex justify-between items-center">
     <div class="flex items-center gap-3">
         <!-- Mobile Sidebar Toggle -->
         <button id="mobile-menu-toggle" class="md:hidden text-slate-600 hover:text-slate-900 focus:outline-none transition">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
             </svg>
         </button>
         <h1 class="text-xl font-bold text-slate-800">
             Admin Dashboard
         </h1>
     </div>

     <div class="flex items-center gap-4">
         <span class="text-slate-600 text-sm"><?= htmlspecialchars($name); ?></span>
         <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
             <?= strtoupper(substr($name, 0, 1)) ?>
         </div>
     </div>
 </header>