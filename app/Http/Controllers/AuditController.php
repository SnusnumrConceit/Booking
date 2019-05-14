<?php

namespace App\Http\Controllers;

use App\Http\Resources\Audit\AuditCollection;
use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $audit = Audit::with('event')->paginate(20);
            return response()->json([
                'audits' => new AuditCollection($audit)
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

}
