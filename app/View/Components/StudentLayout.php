<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StudentLayout extends Component
{
    /**
     * Dapatkan view / konten yang merepresentasikan komponen.
     */
    public function render(): View
    {
        // Pastikan ini menunjuk ke file 'layouts.student'
        return view('layouts.student');
    }
}
