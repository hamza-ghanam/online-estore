<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'DESC')->get();

        if ($suppliers) {
            return MessagesController::responder(200, $suppliers);
        }

        return MessagesController::responder(500, [], 'Cannot fetch suppliers.');
    }
}
