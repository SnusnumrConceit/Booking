<?php

namespace App\Http\Controllers;

use App\Http\Resources\Report\ReportCollection;
use App\User;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $report = Report::where([
                'description' => $request->description,
                'user_id' => $request->user_id
            ])->first();
            if ($report) {
                throw new \Exception('Такой отзыв уже присутствует в системе');
            }
            $report = Report::create($request->input());
            return response()->json([
                'status' => 'success',
                'msg' => 'Отзыв успешно опубликован',
                'report' => new \App\Http\Resources\Report\Report($report)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $reports = Report::with('user')->latest()->paginate(10);
            return response()->json([
                'reports' => new ReportCollection($reports)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $reports = new Report();
            if (isset($request->date)) {
                $date = Carbon::parse($request->date)->format('Y-m-d');
                $start = $date.' 00:00:00';
                $end = $date.' 23:59:59';
                $reports = $reports->whereBetween('created_at', [$start, $end]);
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $reports = $reports->orderBy($filter->name, $filter->type);
                }
            }
            $reports = $reports->with('user')->paginate(10);
            return response()->json([
                'reports' => new ReportCollection($reports)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $report = Report::findOrFail($id);
            return response()->json([
                'report' => $report
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->fill([
                'description' => $request->description,
            ]);
            $report->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Отзыв успешно отредактирован',
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $report = Report::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Отзыв успешно удалён',
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 500);
        }
    }
}
