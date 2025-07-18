<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButtons = document.querySelectorAll('[data-collapse-toggle]');

        dropdownButtons.forEach(button => {
            button.addEventListener('click', function() {
                const dropdownId = this.getAttribute('aria-controls');
                const dropdownMenu = document.getElementById(dropdownId);

                // Toggle the visibility of the dropdown menu
                dropdownMenu.classList.toggle('hidden');
            });
        });
    });

</script>
<style>
    .navbar-sticky-side {
        position: fixed;
    }
</style>


<aside id="sidebar-multi-level-sidebar" class=" w-64 h-screen navbar-sticky-side bg-green-800" aria-label="Sidebar">
    <!-- Header -->
    <header class="bg-white shadow ">
        <div class="max-w-7xl mx-auto h-12 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-12">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-bold text-gray-900">ADMIN</h1>
                </div>
            </div>
        </div>
    </header>

    <div class="h-full px-3 py-4 overflow-y-auto bg-primary">
        {{-- user profile --}}
        <div class="flex items-center">
            <img alt="User profile picture" class="rounded-full" height="50" src="{{ asset('/images/profile.png') }}"
                width="50">
            <ul class="space-y-2 font-medium">
                <li>
                    <div class="ml-4 hidden-on-minimized flex items-center">
                    <span class="text-gray-100 dark:text-white">{{ Auth::guard('admin')->user()->username }}</span>

                    </div>
                </li>
                <li>
                    <div class="ml-4 hidden-on-minimized flex items-center">
                        <span class="text-gray-100 dark:text-white">Role: {{ Auth::guard()->name }}</span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="h-5 py-4 bg-primary">
            <hr class="10px">
        </div>

        <ul class="space-y-2 font-medium">
        <li>
    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <svg class="w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-400 dark:group-hover:text-white"
            aria-hidden="true" xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 22 21">
            <path
                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
            <path
                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
        </svg>
        <span class="text-white text-white flex-1 ms-3 whitespace-nowrap">Dashboard</span>
    </x-nav-link>
</li>

            <li>
                <x-nav-link href="{{ route('admin.manage-user') }}" :active="request()->routeIs('admin.manage-user') || request()->routeIs('admin.create-user') || request()->routeIs('admin.update-user')">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span class="text-white text-white flex-1 ms-3 whitespace-nowrap">Manage Users</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-100 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
    <x-nav-link href="{{ route('admin.manage-unit-pendidikan') }}" :active="request()->routeIs('admin.manage-unit-pendidikan')">
        <!-- Ikon gedung/sekolah -->
        <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
            xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 21v-2h18v2H3Zm16-4V9.83L12 4.12 5 9.83V17h2v-5h10v5h2ZM7 12v3h2v-3H7Zm4 0v3h2v-3h-2Zm4 0v3h2v-3h-2Z"/>
        </svg>
        <span class="text-white flex-1 ms-3 whitespace-nowrap">Manage Unit Pendidikan</span>
        <span
            class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-100 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
    </x-nav-link>
</li>
<li>
    <x-nav-link aria-controls="dropdown-kelas" data-collapse-toggle="dropdown-kelas"
        :active="request()->routeIs('admin.manage-kelas') ||
                 request()->routeIs('admin.create-kelas') ||
                 request()->routeIs('admin.perpindahan-kelas')"
        @click="toggleDropdown()">
        <!-- Ikon utama menu Manage Kelas -->
        <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
            xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M4 4h16v2H4V4zm0 4h10v2H4V8zm0 4h16v2H4v-2zm0 4h10v2H4v-2z"/>
        </svg>
        <span class="text-white flex-1 ms-3 whitespace-nowrap">Manage Kelas</span>
        <svg id="dropdown-arrow" class="w-4 h-4 transition-transform duration-100 transform"
            :class="{ 'rotate-180': dropdownOpen }" xmlns="http://www.w3.org/1000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </x-nav-link>

    <ul id="dropdown-kelas"
        class="py-2 space-y-2 {{ request()->routeIs('admin.manage-kelas') || request()->routeIs('admin.create-kelas') || request()->routeIs('admin.update-kelas') || request()->routeIs('admin.perpindahan-kelas') ? '' : 'hidden' }}">

        <!-- Submenu CRU Kelas -->
        <li>
            <a href="{{ route('admin.manage-kelas') }}"
                class="flex items-center w-full p-2 text-green-100 transition duration-75 rounded-lg pl-11 group hover:bg-green-800 dark:text-white dark:hover:bg-green-700
                {{ request()->routeIs('admin.manage-kelas') || request()->routeIs('admin.create-kelas') || request()->routeIs('admin.update-kelas') ? 'bg-green-700' : '' }}">
                <!-- Ikon CRU -->
                <svg class="w-4 h-4 me-2 text-white dark:text-white" xmlns="http://www.w3.org/1000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-white flex-1 ms-3 whitespace-nowrap">
                CRU Kelas</span>
            </a>
        </li>

        <!-- Submenu Perpindahan Kelas -->
        <li>
            <a href="{{ route('admin.perpindahan-kelas') }}"
                class="flex items-center w-full p-2 text-green-100 transition duration-75 rounded-lg pl-11 group hover:bg-green-500 dark:text-white dark:hover:bg-green-700
                {{ request()->routeIs('admin.perpindahan-kelas') ? 'bg-green-700' : '' }}">
                <!-- Ikon Perpindahan -->
                <svg class="w-4 h-4 me-2 text-white dark:text-white" xmlns="http://www.w3.org/1000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">
                Perpindahan Kelas</span>
            </a>
        </li>
    </ul>
</li>
            <li>
    <x-nav-link href="{{ route('admin.manage-data-siswa') }}" :active="request()->routeIs('admin.manage-data-siswa')">
        <!-- Ikon Siswa / Pendidikan -->
        <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
            xmlns="http://www.w3.org/1000/svg" viewBox="0 0 640 512" fill="currentColor" aria-hidden="true">
            <path d="M622.3 271.1L416 176.5V133c0-7.1-3.8-13.6-10-17.2L336 66.5V32c0-8.8-7.2-16-16-16H48C39.2 16 32 23.2 32 32v400c0 8.8 7.2 16 16 16h272c8.8 0 16-7.2 16-16V233.1l70.2 33.1c-16.2 24.6-25.4 53.6-25.4 84.8 0 88.4 71.6 160 160 160s160-71.6 160-160c0-30.1-8.3-58.3-22.5-82.9z"/>
        </svg>
        <span class="text-white flex-1 ms-3 whitespace-nowrap">Manage Data Siswa</span>
        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-100 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
    </x-nav-link>
</li>

<li>
    <x-nav-link href="{{ route('admin.manage-tahun-ajaran') }}" :active="request()->routeIs('admin.manage-tahun-ajaran')">
        <!-- Ikon Kalender untuk Tahun Ajaran -->
        <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
            xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.89 2 1.99 2H19a2 2 0 0 0 2-2V6c0-1.1-.9-2-2-2Zm0 16H5V10h14v10Zm0-12H5V6h14v2Z"/>
        </svg>
        <span class="text-white flex-1 ms-3 whitespace-nowrap">Manage Tahun Ajaran</span>
        <span
            class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-100 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
    </x-nav-link>
</li>

<li>
    <x-nav-link href="{{ route('admin.manage-jenis-pembayaran') }}" :active="request()->routeIs('admin.manage-jenis-pembayaran')">
        <!-- Ikon Pemasukan / Uang Masuk -->
        <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
            xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 576 512" aria-hidden="true">
            <path d="M568.1 231.3c5-9.8 7.9-20.7 7.9-32.3 0-39.8-32.2-72-72-72h-8V64c0-17.7-14.3-32-32-32H112C94.3 32 80 46.3 80 64v63.1C34.6 144.9 0 185.7 0 232c0 35.3 20.3 66.1 50 81.3C50 406.2 108.2 464 179.3 464H416c88.4 0 160-71.6 160-160 0-25.3-6-49.3-16.5-70.7zM384 288c0 17.7-14.3 32-32 32h-64v16c0 8.8-7.2 16-16 16s-16-7.2-16-16v-16h-64c-17.7 0-32-14.3-32-32s14.3-32 32-32h64v-16c0-8.8 7.2-16 16-16s16 7.2 16 16v16h64c17.7 0 32 14.3 32 32z"/>
        </svg>
        <span class="text-white flex-1 ms-3 whitespace-nowrap">Manage Pemasukan</span>
        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-100 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
    </x-nav-link>
</li>

<li>
    <x-nav-link href="{{ route('admin.manage-data-kas') }}" :active="request()->routeIs('admin.manage-data-kas')">
        <!-- Ikon Dompet / Kas -->
        <svg class="flex-shrink-0 w-5 h-5 text-gray-100 transition duration-75 dark:text-gray-400 group-hover:text-gray-100 dark:group-hover:text-white"
            xmlns="http://www.w3.org/1000/svg" fill="currentColor" viewBox="0 0 576 512" aria-hidden="true">
            <path d="M0 96C0 78.3 14.3 64 32 64H480c17.7 0 32 14.3 32 32v32h32c17.7 0 32 14.3 32 32v256c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm416 160a32 32 0 1 0 0 64 32 32 0 1 0 0-64z"/>
        </svg>
        <span class="text-white flex-1 ms-3 whitespace-nowrap">Manage Jenis Kas</span>
        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-100 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
    </x-nav-link>
</li>







            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <a href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-danger-button class="ms-9">
                            <span class ="text-white flex-1 ms-3 whitespace-nowrap">Logout</span>
                        </x-danger-button>
                    </a>
                </form>
            </li>
        </ul>

    </div>
    <div class="fixed bottom-0">
        <footer class="text-white text-center p-0 w-30">
            <p>copyright &copy; {{ date('Y') }} </p>
            <p>Yayasan Nurul Huda</p>
        </footer>
    </div>
</aside>
