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
                    <h1 class="text-xl font-bold text-gray-900">TUUNIT</h1>
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
                        <span class="text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <x-dropdown align="right" width="40">
                            <x-slot name="trigger">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </li>
                <li>
                    <div class="ml-4 hidden-on-minimized flex items-center">
                        <span class="text-gray-900 dark:text-white">Role: {{ Auth::guard()->name }}</span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="h-5 py-4 bg-primary">
            <hr class="10px">
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <x-nav-link href="{{ route('tuunit.dashboard') }}" :active="request()->routeIs('tuunit.dashboard')">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('tuunit.manage-user') }}" :active="request()->routeIs('tuunit.manage-user')">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Manage Users</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('tuunit.manage-data-siswa') }}" :active="request()->routeIs('tuunit.manage-data-siswa')">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Manage Data Siswa</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('tuunit.manage-jenis-pembayaran') }}" :active="request()->routeIs('tuunit.manage-jenis-pembayaran')">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Manage Jenis Pembayaran</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link aria-controls="dropdown-example" data-collapse-toggle="dropdown-example" :active="request()->routeIs('tuunit.manage-kelas') ||
                    request()->routeIs('tuunit.create-kelas') ||
                    request()->routeIs('tuunit.perpindahan-kelas')"
                    @click="toggleDropdown()">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Manage Kelas</span>
                    <svg id="dropdown-arrow" class="w-4 h-4 transition-transform duration-200 transform"
                        :class="{ 'rotate-180': dropdownOpen }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </x-nav-link>
                <ul id="dropdown-example"
                    class="py-2 space-y-2 {{ request()->routeIs('tuunit.manage-kelas') || request()->routeIs('tuunit.create-kelas') || request()->routeIs('tuunit.update-kelas') || request()->routeIs('tuunit.perpindahan-kelas') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('tuunit.manage-kelas') }}"
                            class="flex items-center w-full p-2 text-green-900 transition duration-75 rounded-lg pl-11 group hover:bg-green-100 dark:text-white dark:hover:bg-green-700
                            {{ request()->routeIs('tuunit.manage-kelas') || request()->routeIs('tuunit.create-kelas') || request()->routeIs('tuunit.update-kelas') ? 'bg-green-900' : '' }}">CRU
                            Kelas</a>
                    </li>
                    <li>
                        <a href="{{ route('tuunit.perpindahan-kelas') }}"
                            class="flex items-center w-full p-2 text-green-900 transition duration-75 rounded-lg pl-11 group hover:bg-green-100 dark:text-white dark:hover:bg-green-700
                            {{ request()->routeIs('tuunit.perpindahan-kelas') ? 'bg-green-900' : '' }}">Perpindahan
                            Kelas</a>
                    </li>
                </ul>
            </li>
            <li>
                <x-nav-link href="{{ route('tuunit.manage-tahun-ajaran') }}" :active="request()->routeIs('tuunit.manage-tahun-ajaran')">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Manage Tahun Ajaran</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('tuunit.manage-unit-pendidikan') }}" :active="request()->routeIs('tuunit.manage-unit-pendidikan')">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Manage Unit Pendidikan</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"></span>
                </x-nav-link>
            </li>
            <li>
                <form method="POST" action="{{ route('tuunit.logout') }}">
                    @csrf
                    <a href="{{ route('tuunit.logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-danger-button class="ms-9">
                            <span class ="flex-1 ms-33 whitespace-nowrap">Logout</span>
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
