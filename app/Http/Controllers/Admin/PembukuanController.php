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
        return view('admin.pembukuan.index');
    }
}
