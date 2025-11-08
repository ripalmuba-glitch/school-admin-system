<x-app-layout>
    {{--
        x-data untuk mengontrol modal pembayaran
        billToPay: data tagihan yang akan dibayar
        paymentAmount: jumlah yang akan dibayar
    --}}
    <div x-data="{
            showPayModal: false,
            billToPay: null,
            paymentAmount: 0,
            setBill(bill) {
                this.billToPay = bill;
                this.paymentAmount = bill.amount - bill.amount_paid; // Set default ke sisa tagihan
                this.showPayModal = true;
            }
         }">

        <x-slot name="header">
            Tagihan Siswa: {{ $student->full_name }} (NISN: {{ $student->nisn }})
        </x-slot>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

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
                                            <button @click="setBill({{ $bill }})" class="text-xs text-blue-500 hover:underline">Bayar</button>
                                        @else
                                            <span class="text-xs font-bold text-red-600 dark:text-red-400">Belum Lunas</span>
                                            <button @click="setBill({{ $bill }})" class="text-xs text-blue-500 hover:underline">Bayar</button>
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
                                        <button @click="setBill({{ $bill }})" class="text-sm text-blue-500 hover:underline ml-2">Bayar</button>
                                    @else
                                        <span class="px-3 py-1 text-sm font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 rounded-full">Belum Lunas</span>
                                        <button @click="setBill({{ $bill }})" class="text-sm text-blue-500 hover:underline ml-2">Bayar</button>
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
                                    {{ $tx->transaction_date->format('d M Y') }} - {{ $tx->transaction_code }}
                                </p>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>

        {{-- MODAL PEMBAYARAN --}}
        <div x-show="showPayModal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="display: none;">

            <div @click.away="showPayModal = false"
                 class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-title">
                    Proses Pembayaran
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Siswa: <strong>{{ $student->full_name }}</strong>
                </p>

                <form action="{{ route('admin.payments.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="bill_id" x-bind:value="billToPay ? billToPay.id : ''">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tagihan</label>
                        <input type="text" x-bind:value="billToPay ? `${billToPay.payment_type.name} ${billToPay.month ? '(Bulan ' + new Date(0, billToPay.month - 1).toLocaleString('id-ID', { month: 'long' }) + ')' : ''}` : ''"
                               readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sisa Tagihan</label>
                        <input type="text" x-bind:value="billToPay ? 'Rp. ' + (billToPay.amount - billToPay.amount_paid).toLocaleString('id-ID') : ''"
                               readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Bayar</label>
                        <input type="number" name="amount" x-model="paymentAmount" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pembayaran</label>
                        <input type="date" name="transaction_date" value="{{ now()->format('Y-m-d') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('transaction_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan (Opsional)</label>
                        <input type="text" name="notes" placeholder="Transfer, Tunai, dll."
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="button" @click="showPayModal = false" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
