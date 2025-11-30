<body class="bg-gray-100">
    <div class="lg:hidden fixed not-only:top-4 left-4 z-50">
        <button id="mobileMenuBtn" class="p-2 rounded-md bg-indigo-600 text-white shadow-lg">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>
    
    <div id="sidebar" class="sticky top-0 lg:static inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out min-h-screen flex flex-col">
        <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200 shrink-0">
            <a href="/dashboard" class="flex items-center space-x-2">
                <span class="text-2xl font-bold text-indigo-600">📚 Perpustakaan</span>
            </a>
        </div>
        
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="px-4 space-y-2">
                <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-colors duration-200 active-link">
                    <i class="fa-solid fa-chart-line w-5 mr-3 text-center"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="relative">
                    <button id="kelolaMenuBtn" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fa-solid fa-gears w-5 mr-3 text-center"></i>
                            <span class="font-medium">Kelola</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    
                    <div id="kelolaDropdown" class="hidden mt-1 ml-4 space-y-1 border-l border-gray-200 pl-4">
                        <a href="/pengguna" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-200">
                            <i class="fa-solid fa-users w-4 mr-2 text-center"></i>
                            <span>Pengguna</span>
                        </a>
                        <a href="/buku" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-200">
                            <i class="fa-solid fa-book-open w-4 mr-2 text-center"></i>
                            <span>Buku</span>
                        </a>
                    </div>
                </div>

                <a href="/peminjaman" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-colors duration-200 active-link">
                    <i class="fa-solid fa-clock w-8"></i>
                    <span class="font-medium">Peminjaman</span>
                </a>

                <!-- Profil -->
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 group transition-colors duration-200">
                    <i class="fa-regular fa-user w-5 mr-3 text-center"></i>
                    <span class="font-medium">Profil</span>
                </a>
            </nav>
        </div>

        <div class="border-t border-gray-200 p-4 shrink-0">
            <div class="relative">
                <button id="profileMenuBtn" class="flex items-center w-full px-2 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama_lengkap }}&background=6366F1&color=fff" 
                         class="w-8 h-8 rounded-full mr-3">
                    <div class="flex-1 text-left">
                        <p class="font-medium text-sm">{{ Auth::user()->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
                </button>

                <div id="profileDropdown" class="hidden absolute bottom-full left-0 mb-2 w-full bg-white rounded-lg shadow-lg py-2 border border-gray-200 z-10">
                    <a href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fa-regular fa-user mr-2 w-4 text-center"></i> 
                        <span>Profil Saya</span>
                    </a>
                    <a href="/settings" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fa-solid fa-gear mr-2 w-4 text-center"></i> 
                        <span>Pengaturan</span>
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fa-solid fa-right-from-bracket mr-2 w-4 text-center"></i> 
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const mainContent = document.getElementById('mainContent');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        const kelolaMenuBtn = document.getElementById('kelolaMenuBtn');
        const kelolaDropdown = document.getElementById('kelolaDropdown');
        const kelolaIcon = kelolaMenuBtn.querySelector('.fa-chevron-down');

        kelolaMenuBtn.addEventListener('click', () => {
            kelolaDropdown.classList.toggle('hidden');
            kelolaIcon.classList.toggle('rotate-180');
        });

        const profileMenuBtn = document.getElementById('profileMenuBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        profileMenuBtn.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileMenuBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
            
            if (!kelolaMenuBtn.contains(e.target) && !kelolaDropdown.contains(e.target)) {
                kelolaDropdown.classList.add('hidden');
                kelolaIcon.classList.remove('rotate-180');
            }
        });
    </script>
</body>