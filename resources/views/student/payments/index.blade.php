<x-student-layout>
    <x-slot name="header">
        Detail Tagihan Keuangan
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI (Tagihan) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Tagihan Bulanan (SPP) --}}
            @foreach ($monthlyBills as $paymentName => $bills)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-6 border-b dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $paymentName }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tahun Ajaran {{ $activeYear->year_name }}</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
                        @php $months = [7=>'Juli', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des', 1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun']; @endphp

                        @foreach ($months as $monthNum => $monthName)
                            @php $bill = $bills->firstWhere('month', $monthNum); @endphp
                            @if ($bill)
                                <div class="p-3 rounded-lg border dark:border-gray-700
                                    {{ $bill->status == 'paid' ? 'bg-green-50 dark:bg-green-900 border-green-200 dark:border-green-700' : 'bg-gray-50 dark:bg-gray-700' }}">
                                    <p class="font-semibold text-gray-800 dark:text-white">{{ $monthName }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Rp. {{ number_format($bill->amount, 0, ',', '.') }}</p>

                                    @if ($bill->status == 'paid')
                                        <span class="text-xs font-bold text-green-600 dark:text-green-400">LUNAS</span>
                                    @elseif ($bill->status == 'partially_paid')
                                        <span class="text-xs font-bold text-yellow-600 dark:text-yellow-400">Kurang Rp. {{ number_format($bill->amount - $bill->amount_paid, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-xs font-bold text-red-600 dark:text-red-400">Belum Lunas</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Tagihan Sekali Bayar --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tagihan Lainnya</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tahun Ajaran {{ $activeYear->year_name }}</p>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($onceBills as $bill)
                        <li class="p-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $bill->paymentType->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Rp. {{ number_format($bill->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                @if ($bill->status == 'paid')
                                    <span class="px-3 py-1 text-sm font-bold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 rounded-full">LUNAS</span>
                                @elseif ($bill->status == 'partially_paid')
                                    <span class="px-3 py-1 text-sm font-bold text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                                        Kurang Rp. {{ number_format($bill->amount - $bill->amount_paid, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 rounded-full">Belum Lunas</span>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="p-4 text-center text-gray-500 dark:text-gray-400">Tidak ada tagihan lainnya.</li>
                    @endforelse
                </ul>
            </div>

        </div>

        {{-- KOLOM KANAN (Riwayat Transaksi) --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Riwayat Transaksi Terakhir</h3>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($recentTransactions as $tx)
                        <li class="p-4">
                            <p class="font-semibold text-gray-800 dark:text-white">Rp. {{ number_format($tx->amount, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $tx->bill?->paymentType?->name ?? 'Pembayaran' }}
                                @if($tx->bill?->month)
                                    (Bulan: {{ date('F', mktime(0, 0, 0, $tx->bill->month, 10)) }})
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $tx->transaction_date->format('d M Y') }} - Dicatat oleh {{ $tx->admin->name ?? 'Sistem' }}
                            </p>
                        </li>
                    @empty
                        <li class="p-4 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</x-student-layout>
