<x-app-layout>
    <x-slot name="header">
        Laporan Tunggakan
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Laporan Tunggakan Keuangan
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Daftar semua tagihan yang belum lunas untuk Tahun Ajaran Aktif ({{ $activeYear->year_name ?? 'N/A' }}).
            </p>
        </div>

        @if ($arrears->isEmpty())
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                Tidak ada tunggakan yang ditemukan untuk tahun ajaran ini. Semua tagihan lunas!
            </div>
        @else
            <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tagihan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kekurangan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($arrears as $bill)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $bill->student->full_name ?? 'Siswa Dihapus' }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $bill->student->nisn ?? 'N/A' }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $bill->paymentType->name ?? 'N/A' }}
                                    @if($bill->month)
                                        (Bulan: {{ date('F', mktime(0, 0, 0, $bill->month, 10)) }})
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-semibold">
                                    Rp. {{ number_format($bill->amount - $bill->amount_paid, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($bill->status == 'partially_paid')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Bayar Sebagian
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.payments.show', $bill->student_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">
                                        Proses Pembayaran &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $arrears->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
