{{--
Ini adalah file Blade HANYA untuk template Excel/PDF.
File ini sengaja dibuat dalam format tabel HTML sederhana.
--}}
<table>
    <thead>
        {{-- Informasi Header Laporan --}}
        <tr>
            <th colspan="4" style="text-align: center;"><strong>LAPORAN ABSENSI SISWA</strong></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;"><strong>Kelas:</strong> {{ $classroom->name ?? 'Semua Kelas' }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;"><strong>Mata Pelajaran:</strong> {{ $subject->name ?? 'Semua Mapel' }}</th>
        </tr>
         <tr>
            <th colspan="4" style="text-align: center;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</th>
        </tr>
        <tr>
            {{-- Spasi kosong --}}
        </tr>
        {{-- Header Tabel --}}
        <tr style="border-bottom: 2px solid #000;">
            <th style="font-weight: bold;">NAMA SISWA</th>
            <th style="font-weight: bold;">NISN</th>
            <th style="font-weight: bold;">STATUS</th>
            <th style="font-weight: bold;">CATATAN</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($attendances as $attendance)
        <tr>
            <td>{{ $attendance->student->full_name }}</td>
            <td>{{ $attendance->student->nisn }}</td>
            <td>{{ $attendance->status }}</td>
            <td>{{ $attendance->notes ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align: center;">Tidak ada data absensi untuk filter ini.</td>
        </tr>
    @endforelse
    </tbody>
</table>
