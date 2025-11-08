<x-app-layout>
    <x-slot name="header">
        Manajemen Pengguna (Role)
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        {{-- PERBAIKAN: Tambahkan 'text-center' untuk menengahkan judul --}}
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Edit Pengguna: {{ $user->name }}
        </h2>

        {{-- PERBAIKAN: Tambahkan 'mx-auto' untuk menengahkan form --}}
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6 max-w-lg mx-auto">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role / Peran</label>
                <select name="role_id" id="role_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        {{ auth()->user()->id === $user->id ? 'disabled' : '' }}> {{-- Admin tidak bisa ganti role sendiri --}}
                    <option value="">Pilih Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @if(auth()->user()->id === $user->id)
                    <small class="text-gray-500">Anda tidak dapat mengubah role Anda sendiri.</small>
                    <input type="hidden" name="role_id" value="{{ $user->role_id }}" />
                @endif
                @error('role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Kosongkan jika tidak ingin ganti">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Perbarui Pengguna
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
