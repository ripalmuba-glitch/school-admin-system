{{--
Ini adalah file Blade HANYA untuk template PDF.
Gunakan <table> dan inline style sederhana. JANGAN gunakan Tailwind/Grid/Flex.
--}}
<html>
<head>
    <title>Rapor Siswa</title>
    <style>
        /* Gaya CSS paling dasar yang aman untuk dompdf.
           Semua layout dikontrol oleh <table>.
        */
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
        }
        table {
            border-collapse: collapse; /* Ini penting */
            width: 100%;
        }
        /* Tabel master yang membungkus SELURUH rapor */
        .master-table {
            border: 1px solid #000; /* Ini adalah bingkai luar rapor */
        }
        .master-table th, .master-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top; /* Default semua ke atas */
        }

        /* Kelas 'no-border' dan 'no-padding' digunakan untuk sel
           yang fungsinya hanya untuk layout, bukan untuk data.
        */
        .no-border {
            border: none !important;
        }
        .no-padding {
            padding: 0 !important;
        }
        .no-border-top-bottom {
            border-top: none !important;
            border-bottom: none !important;
        }

        /* --- STYLING SPESIFIK --- */

        /* Header (Kop Surat) */
        .header-cell {
            text-align: center;
            vertical-align: middle;
            border-bottom: 2px solid #000 !important;
            padding-bottom: 10px;
        }
        .header-cell img {
            width: 60px; /* Ukuran logo tetap */
            height: auto;
        }
        .header-cell h1 {
            font-size: 14pt;
            margin: 0;
            font-weight: bold;
        }
        .header-cell h2 {
            font-size: 11pt;
            margin: 3px 0 0 0;
            font-weight: normal;
        }
        .header-cell p {
            font-size: 8pt;
            margin: 0;
        }

        /* Info Siswa (Nested table) */
        .info-table {
            width: 100%;
        }
        .info-table td {
            border: none;
            padding: 2px 4px;
        }

        /* Judul Bagian (A. SIKAP, B. NILAI) */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
        }

        /* Tabel Nilai & Absensi */
        .grades-header th {
            background-color: #E9ECEF;
            font-weight: bold;
            text-align: center;
        }
        .attendance-table {
            width: 100%;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #000;
            text-align: center;
            font-size: 10pt;
            padding: 4px;
        }

        /* Tanda Tangan */
        .signature-cell {
            text-align: center;
        }
        .signature-space {
            height: 60px;
        }

        /* Utility */
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    {{-- Ini adalah tabel master yang membungkus segalanya --}}
    <table class="master-table">

        <tr class="no-border">
            <td class="header-cell no-border" style="width: 20%; text-align: center;">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Logo" style="width: 65px; height: auto;">
                @else
                    [Logo]
                @endif
            </td>
            <td class="header-cell no-border" style="width: 80%; text-align: center;">
                <h1>LAPORAN HASIL BELAJAR SISWA</h1>
                <h2>{{ $settings['school_name'] ?? 'SEKOLAH ANDA' }}</h2>
                <p>
                    {{ $settings['school_address'] ?? 'Alamat Sekolah' }} -
                    Telp: {{ $settings['school_phone'] ?? '(021) 123456' }}
                </p>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="no-padding no-border-top-bottom">
                <table class="info-table">
                    <tr>
                        <td class="no-border" style="width: 15%;" class="font-bold">Nama Siswa</td>
                        <td class="no-border" style="width: 1%;">:</td>
                        <td class="no-border" style="width: 44%;">{{ $student->full_name }}</td>
                        <td class="no-border" style="width: 15%;" class="font-bold">Tahun Ajaran</td>
                        <td class="no-border" style="width: 1%;">:</td>
                        <td class="no-border" style="width: 24%;">{{ $activeYear->year_name }}</td>
                    </tr>
                    <tr>
                        <td class="no-border" class="font-bold">NISN</td>
                        <td class="no-border">:</td>
                        <td class="no-border">{{ $student->nisn }}</td>
                        <td class="no-border" class="font-bold">Kelas</td>
                        <td class="no-border">:</td>
                        <td class="no-border">{{ $currentClassroom->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="font-bold section-title">A. SIKAP</td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px; font-style: italic;">
                Siswa menunjukkan sikap (TODO: Modul Sikap) yang baik dalam pembelajaran.
            </td>
        </tr>

        <tr>
            <td colspan="2" class="font-bold section-title">B. PENGETAHUAN DAN KETERAMPILAN</td>
        </tr>
        <tr>
            <td colspan="2" class="no-padding">
                <table style="width: 100%;">
                    <tr class="grades-header">
                        <th style="width: 5%;">No.</th>
                        <th style="width: 45%;">Mata Pelajaran</th>
                        <th class="text-center" style="width: 12%;">Tugas</th>
                        <th class="text-center" style="width: 12%;">UH</th>
                        <th class="text-center" style="width: 12%;">UTS</th>
                        <th class="text-center" style="width: 12%;">UAS</th>
                    </tr>
                    @php $i = 1; @endphp
                    @forelse ($gradesBySubject as $subjectName => $grades)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td>
                                {{ $subjectName }}
                                <br>
                                <span style="font-size: 8pt; color: #555;">(Guru: {{ $grades->first()->teacher->full_name ?? 'N/A' }})</span>
                            </td>
                            <td class="text-center">{{ number_format($grades->firstWhere('grade_type', 'Tugas Harian')?->score, 0) ?? '-' }}</td>
                            <td class="text-center">{{ number_format($grades->firstWhere('grade_type', 'Ulangan Harian')?->score, 0) ?? '-' }}</td>
                            <td class="text-center">{{ number_format($grades->firstWhere('grade_type', 'UTS')?->score, 0) ?? '-' }}</td>
                            <td class="text-center">{{ number_format($grades->firstWhere('grade_type', 'UAS')?->score, 0) ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 20px;">Belum ada data nilai yang diinput.</td>
                        </tr>
                    @endforelse
                </table>
            </td>
        </tr>

        <tr class="no-border">
            <td class="no-border" style="width: 50%; padding: 10px;">
                <span class="font-bold">C. KETIDAKHADIRAN</span>
                <table class="attendance-table" style="margin-top: 5px;">
                    <thead class="grades-header">
                        <tr>
                            <th>Sakit (S)</th>
                            <th>Izin (I)</th>
                            <th>Alpa (A)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>{{ $attendanceSummary['Sakit'] ?? 0 }} hari</td>
                            <td>{{ $attendanceSummary['Izin'] ?? 0 }} hari</td>
                            <td>{{ $attendanceSummary['Alpa'] ?? 0 }} hari</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="signature-cell no-border" style="width: 50%; padding-top: 25px;">
                Wali Kelas,
                <div class="signature-space"></div>
                <span style="font-weight: bold; text-decoration: underline;">(Nama Wali Kelas - TODO)</span>
                <br>
                <span>NIP. (NIP Wali Kelas - TODO)</span>
            </td>
        </tr>

    </table>

</body>
</html>
