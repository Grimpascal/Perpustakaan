<body class="bg-gray-100">
    <nav class="bg-white border-b border-gray-200 sticky">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="/dashboard" class="shrink flex items-center">
                        <span class="text-2xl font-bold text-indigo-600">📚 Perpustakaan</span>
                    </a>

                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="/dashboard" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 border-b-2 border-indigo-500">
                            Dashboard
                        </a>

                        <a href="/favorite" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent">
                            Favorit
                        </a>
                        <a href="/peminjaman" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent">
                            Peminjaman
                        </a>
                        <a href="/history" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent">
                            Riwayat Peminjaman
                        </a>

                        <a href="/profile" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent">
                            Profil
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center relative">
                    <button id="profileMenuBtn" 
                        class="flex items-center space-x-2 px-3 py-2 rounded hover:bg-gray-100 transition">
                        
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama_lengkap }}&background=6366F1&color=fff" 
                             class="w-8 h-8 rounded-full">

                        <span class="text-gray-700 font-medium">
                            {{ Auth::user()->nama_lengkap }}
                        </span>

                        <i class="fa-solid fa-chevron-down text-sm ml-1"></i>
                    </button>

                    <!-- Dropdown -->
                    <div id="profileDropdown" 
                        class="hidden absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg py-2 border">

                        <a href="/profile" 
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fa-regular fa-user mr-2"></i> Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <button id="mobileBtn" type="button" 
                        class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>

            </div>
        </div>

        <div id="mobileMenu" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="/dashboard" class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700">
                    Dashboard
                </a>
                <a href="/books" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300">
                    Buku
                </a>
                <a href="/profile" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300">
                    Profil
                </a>
            </div>

            <div class="pt-4 pb-3 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}" class="px-4">
                    @csrf
                    <button class="w-full text-left px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <script>
        const btn = document.getElementById('profileMenuBtn');
        const dropdown = document.getElementById('profileDropdown');

        btn?.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        const mobileBtn = document.getElementById('mobileBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>