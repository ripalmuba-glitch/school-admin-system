<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-75 lg:hidden" @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-cyan-800 text-white flex flex-col transform lg:translate-x-0"
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
     x-transition:enter="transition ease-in-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full">

    <div class="h-16 flex items-center justify-center px-4 bg-cyan-900 shadow-md">
        <h1 class="text-xl font-bold">DASBOR SISWA</h1>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-2 sidebar-scroll">

        <a href="{{ route('student.dashboard') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('student.dashboard') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            <x-icons.dashboard class="h-5 w-5 mr-3" />
            Dasbor
        </a>

        <div class="space-y-1">
            <button @click="akademikMenuOpen = !akademikMenuOpen"
                    class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-cyan-100 hover:bg-cyan-700 focus:outline-none">
                <div class="flex items-center">
                    <x-icons.academic class="h-5 w-5 mr-3" />
                    <span>Akademik</span>
                </div>
                <x-icons.arrow-right class="h-4 w-4 transform transition-transform duration-150" x-bind:class="akademikMenuOpen ? 'rotate-90' : ''" />
            </button>

            <div x-show="akademikMenuOpen" x-transition class="pl-5 space-y-1">
                <a href="{{ route('student.schedule.index') }}"
                   class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                          {{ request()->routeIs('student.schedule.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
                   Jadwal Pelajaran
                </a>
                <a href="{{ route('student.grades.index') }}"
                   class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                          {{ request()->routeIs('student.grades.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
                   Laporan Nilai
                </a>
                <a href="{{ route('student.attendance.index') }}"
                   class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                          {{ request()->routeIs('student.attendance.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
                   Laporan Absensi
                </a>
            </div>
        </div>

        <div class="space-y-1">
            <button @click="keuanganMenuOpen = !keuanganMenuOpen"
                    class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-cyan-100 hover:bg-cyan-700 focus:outline-none">
                <div class="flex items-center">
                    <x-icons.dollar class="h-5 w-5 mr-3" />
                    <span>Keuangan</span>
                </div>
                <x-icons.arrow-right class="h-4 w-4 transform transition-transform duration-150" x-bind:class="keuanganMenuOpen ? 'rotate-90' : ''" />
            </button>

            <div x-show="keuanganMenuOpen" x-transition class="pl-5 space-y-1">
                {{-- PERUBAHAN DI SINI --}}
                <a href="{{ route('student.payments.index') }}"
                   class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                          {{ request()->routeIs('student.payments.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
                   Lihat Tagihan
                </a>
            </div>
        </div>

    </nav>

    <div class="p-4 border-t border-cyan-700">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-cyan-100 hover:bg-cyan-700 group">
            <x-icons.profile class="h-5 w-5 mr-3" />
            Profil Saya
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-cyan-100 hover:bg-cyan-700 group mt-1">
                <x-icons.logout class="h-5 w-5 mr-3" />
                Logout
            </a>
        </form>
    </div>
</div>
