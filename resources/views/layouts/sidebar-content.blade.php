<div class="space-y-1">
    <button @click="adminMenuOpen = !adminMenuOpen"
            class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-cyan-100 hover:bg-cyan-700 focus:outline-none">
        <div class="flex items-center">
            <x-icons.dashboard class="h-5 w-5 mr-3" />
            <span>Admin</span>
        </div>
        <x-icons.arrow-right class="h-4 w-4 transform transition-transform duration-150" x-bind:class="adminMenuOpen ? 'rotate-90' : ''" />
    </button>

    <div x-show="adminMenuOpen" x-transition class="pl-5 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.users.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Manajemen Role
        </a>
        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.settings.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Pengaturan Sekolah
        </a>
    </div>
</div>

<div class="space-y-1">
    <button @click="masterDataMenuOpen = !masterDataMenuOpen"
            class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-cyan-100 hover:bg-cyan-700 focus:outline-none">
        <div class="flex items-center">
            <x-icons.database class="h-5 w-5 mr-3" />
            <span>Master Data</span>
        </div>
        <x-icons.arrow-right class="h-4 w-4 transform transition-transform duration-150" x-bind:class="masterDataMenuOpen ? 'rotate-90' : ''" />
    </button>

    <div x-show="masterDataMenuOpen" x-transition class="pl-5 space-y-1">
        <a href="{{ route('admin.teachers.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.teachers.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Modul Guru
        </a>
        <a href="{{ route('admin.students.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.students.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Modul Siswa
        </a>
        <a href="{{ route('admin.classrooms.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.classrooms.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Modul Kelas
        </a>
        <a href="{{ route('admin.subjects.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.subjects.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Modul Mata Pelajaran
        </a>
        <a href="{{ route('admin.academicyears.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.academicyears.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Modul Tahun Ajaran
        </a>
    </div>
</div>

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
        <a href="{{ route('admin.schedules.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.schedules.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Modul Jadwal
        </a>
        <a href="{{ route('admin.attendances.create') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.attendances.create') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Ambil Absensi
        </a>
        <a href="{{ route('admin.attendances.report') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.attendances.report') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Laporan Absensi
        </a>
        <a href="{{ route('admin.grades.create') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.grades.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Input Nilai
        </a>
        {{-- TAUTAN BARU DI SINI --}}
        <a href="{{ route('admin.promotions.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.promotions.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Kenaikan Kelas
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
        <a href="{{ route('admin.payment_types.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.payment_types.*') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Jenis Pembayaran
        </a>
        <a href="{{ route('admin.payments.index') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.payments.index') || request()->routeIs('admin.payments.show') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Transaksi Pembayaran
        </a>
        <a href="{{ route('admin.payments.arrears') }}"
           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                  {{ request()->routeIs('admin.payments.arrears') ? 'bg-cyan-900 text-white' : 'text-cyan-100 hover:bg-cyan-700' }}">
            Laporan Tunggakan
        </a>
    </div>
</div>
