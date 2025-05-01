<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ponsel;
use App\Models\Ulasan;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        
        $produkTerbaru = Ponsel::orderBy('created_at', 'desc')->take(4)->get();

        // Ambil data customer yang sedang login
        $customer = Auth::user();
        
    
        return view('customer.home', compact('produkTerbaru', 'customer'));
    }
}
