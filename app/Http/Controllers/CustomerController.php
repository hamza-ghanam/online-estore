<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'DESC')->get();

        if ($customers) {
            return MessagesController::responder(200, $customers);
        }

        return MessagesController::responder(500, [], 'Cannot fetch products.');
    }
}
