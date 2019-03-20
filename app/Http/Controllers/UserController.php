<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserInfo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function signin(Request $request)
    {
//        $user = User::where([
//            'email' => $request->email,
//            'password' => Hash::make($request->password)
//        ])->count();
//        if (! $user) {
//            return response()->json([
//                'status' => 'error',
//                'msg' => 'Неверные данные'
//            ]);
//        }
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Неверные данные'
                ]);
            }
            return response()->json([
                'token' => $token,
                'user' => $this->getUser()
            ], 200);
        } catch (JWTException $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function registration(Request $request)
    {
        $result = $this->create($request);
        return $this->signin($request);
    }

    public function logout()
    {
      auth()->logout();
    }

    public function getUser()
    {
        return auth()->user();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $user = User::where([
                'email' => $request->email
            ])->count();
            if ($user) {
                throw new \Exception('Такой пользователь существует в системе');
            }
            $user = new User();
            $user->fill([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'birthday' => $this->convertDate($request->birthday)
            ]);
            $user->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Пользователь успешно добавлен'
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
            $users = User::paginate(10);
            return response()->json([
                'users' => new UserCollection($users)
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
            $users = new User();
            if (isset($request->keyword)) {
                $users = $users->where('last_name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $users = $users->orderBy($filter->name, $filter->type);
                }
            }
            $users = $users->paginate(10);
            return response()->json([
                'users' => new UserCollection($users)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function info(int $id)
    {
        try {
         $user = User::with('orders')->findOrFail($id);
         return response()->json([
             'user_info' => new UserInfo($user)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $user = User::where([
                'email' => $request->email
            ])->count();
            if ($user > 1) {
                throw new \Exception('Такой пользователь существует в системе');
            }
            $user = User::findOrFail($id);
            $user->fill([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'birthday' => $this->convertDate($request->birthday)
            ]);
            $user->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Сведения о пользователе успешно обновлёны'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Пользователь успешно удалён'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}
