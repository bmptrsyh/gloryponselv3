<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chat;
use App\Models\Ponsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $bulan = [
            'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        ];
    
        $currentMonth = date('F');
        $currentMonthIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];
    
        $selectedMonth = $currentMonthIndo[$currentMonth];
    
        return view('admin.dashboard', compact('bulan', 'selectedMonth'));
    }
    
}
