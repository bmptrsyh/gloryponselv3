<?php

namespace App\Http\Controllers\Admin;

use App\Models\BeliPonsel;
use App\Models\JualPonsel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KreditPonsel;
use App\Models\TukarTambah;

class PembukuanController extends Controller
{
    public function index()
    {
        $laporan = collect()
            ->merge(BeliPonsel::all())
            ->merge(JualPonsel::all())
            ->merge(KreditPonsel::all())
            ->merge(TukarTambah::all());
        dd($laporan->sortByDesc('created_at')->values()->all());
        return view('admin.pembukuan.index', compact('laporan'));
    }
}
