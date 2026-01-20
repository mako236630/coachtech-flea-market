<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index($item_id)
    {
        return view("user.purchase-address");
    }
}
