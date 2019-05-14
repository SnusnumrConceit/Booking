<?php

namespace App\Http\Controllers;

use App\Events\WriteAudit;
use App\Exceptions\ControllerException;
use App\Http\Requests\Appointment\AppointmentFormRequest;
use App\Http\Requests\Employee\EmployeeFormRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AppointmentFormRequest $request)
    {
        try {
            $validation = $request->validated();
            $appointment = Appointment::where('name', $request->appoitment)->count();
            if ($appointment) {
                throw (new ControllerException('Такая должность уже существует'));
            }
            $appointment = new Appointment();
            $appointment->fill([
                'name' => $request->appointment
            ]);
            $appointment->save();
            event(new WriteAudit((object)[
                'id'    => $appointment->id,
                'name'  => $appointment->name,
                'type'  => 'appointment'
            ], 1, 12));
            return response()->json([
                'status' => 'success',
                'msg' => 'Должность успешно добавлена'
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
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
            $appointments = ($request->page) ? Appointment::paginate(10) : Appointment::all();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $appointments = new Appointment();
            if (isset($request->keyword)) {
                $appointments = $appointments->where('name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->name)) {
                    $appointments = $appointments->orderBy($filter->name, $filter->type);
                }
            }
            $appointments = $appointments->paginate(10);
            return response()->json([
                'appointments' => $appointments
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
     * @param  \App\appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function form_info(Request $request, int $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            return response()->json([
                'appointment' => $appointment
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentFormRequest $request, int $id)
    {
        try {
            $validation = $request->validated();
            $appointment = Appointment::where('name', $request->appointment)->count();
            if ($appointment) {
                throw new \Exception('Такая должность уже существует');
            }
            $appointment = Appointment::findOrFail($id);
            $appointment->fill([
                'name' => $request->appointment
            ]);
            $appointment->save();
            event(new WriteAudit((object)[
                'id'    => $appointment->id,
                'name'  => $appointment->name,
                'type'  => 'appointment'
            ], 1, 13));
            return response()->json([
                'status' => 'success',
                'msg' => 'Должность успешно обновлена'
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $name = $appointment->name;
            $appointment->delete();
            event(new WriteAudit((object)[
                'name'  => $name,
                'type'  => 'appointment'
            ], 1, 14));
            return response()->json([
                'status' => 'success',
                'msg' => 'Должность успешно удалена'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
