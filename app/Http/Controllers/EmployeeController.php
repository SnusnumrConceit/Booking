<?php

namespace App\Http\Controllers;

use App\Events\WriteAudit;
use App\Http\Requests\Employee\EmployeeFormRequest;
use App\Http\Resources\Employee\EmployeeCollection;
use App\Models\Appointment;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(EmployeeFormRequest $request)
    {
        try {
            $validation = $request->validated();
            $employee = Employee::where([
                'last_name'     => $request->last_name,
                'first_name'    => $request->first_name,
                'middle_name'   => $request->middle_name,
                'birthday'      => $request->date_of_birth,
            ])->count();
            if ($employee) {
                throw new \Exception('Такой работник уже присутствует в системе');
            }
            $employee = new Employee();
            $employee->fill($request->input());
            $employee->birthday = Carbon::parse($employee->birthday)->format('Y-m-d');
            $employee->save();
            event(new WriteAudit((object)[
                'id'    => $employee->id,
                'name' => $employee->last_name . ' ' . $employee->first_name,
                'type'  => 'employee'
            ], 1, 7));
            return response()->json([
                'status' => 'success',
                'msg' => 'Работник успешно добавлен'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
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
            $employees = ($request->page)
                ? Employee::with('appointment')->paginate(10)
                : Employee::with('appointment')->all();
            return response()->json([
                'employees' => new EmployeeCollection($employees)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $employees = new Employee();
//            $employees = $employees->with('appointment');
            if (isset($request->keyword)) {
                $employees = $employees->where('last_name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    if ($filter->name === 'appointment') {
                        $employees = (new Employee())->sortedAppointment($filter->type);
                    } else {
                        $employees = $employees->orderBy($filter->name, $filter->type);
                    }
                }
            }
            $employees = $employees->paginate(10);
            return response()->json([
                'employees' => new EmployeeCollection($employees)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response()->json([
                'employee' => $employee
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    public function form_info()
    {
        try {
            $appointments = Appointment::all();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeFormRequest $request, int $id)
    {
        try {
            $validation = $request->validated();
            $employee = Employee::where([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'birthday' => $request->date_of_birth,
            ])->count();
            if ($employee) {
                throw new \Exception('Такой работник уже присутствует в системе');
            }
            $employee = Employee::findOrFail($id);
            $employee->fill($request->input());
            $employee->birthday = Carbon::parse($employee->birthday)->format('Y-m-d');
            $employee->save();
            event(new WriteAudit((object)[
                'id'    => $employee->id,
                'name' => $employee->last_name . ' ' . $employee->first_name,
                'type'  => 'employee'
            ], 1, 8));
            return response()->json([
                'status' => 'success',
                'msg'    => 'Запись о работнике успешно обновлена'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            event(new WriteAudit((object)[
                'name' => $employee->last_name . ' ' . $employee->first_name,
                'type'  => 'employee'
            ], 1, 9));
            return response()->json([
                'status' => 'success',
                'msg' => 'Запись о работнике успешно удалена'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }
}
