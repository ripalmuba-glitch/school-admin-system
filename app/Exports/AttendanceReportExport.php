<?php

namespace App\Exports;

use IlluminateZ\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceReportExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $attendances;
    protected $classroom;
    protected $subject;
    protected $date;

    // Kita akan mengirim data laporan ke class ini
    public function __construct($attendances, $classroom, $subject, $date)
    {
        $this->attendances = $attendances;
        $this->classroom = $classroom;
        $this->subject = $subject;
        $this->date = $date;
    }

    /**
     * Mengarahkan ke file view (Blade) yang akan dijadikan template.
     */
    public function view(): View
    {
        return view('admin.attendances.export', [
            'attendances' => $this->attendances,
            'classroom' => $this->classroom,
            'subject' => $this->subject,
            'date' => $this->date,
        ]);
    }

    /**
     * Memberikan styling (agar "rapi").
     */
    public function styles(Worksheet $sheet)
    {
        // Set Judul Laporan (Baris 1-4)
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal('center');

        // Set Header Tabel (Baris 6)
        $sheet->getStyle('A6:D6')->getFont()->setBold(true);
        $sheet->getStyle('A6:D6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        // Set Border untuk semua data
        $lastRow = count($this->attendances) + 6; // 6 = offset header
        $sheet->getStyle('A6:D' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}
