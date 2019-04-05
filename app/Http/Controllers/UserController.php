<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllerException;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Http\Requests\Auth\RegistrationFormRequest;
use App\Http\Requests\User\UserFormRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserInfo;
use App\Http\Resources\User\UserVuex;
use App\Models\RoleUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use Monolog\Handler\IFTTTHandler;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;


class UserController extends Controller
{
    public function signin(LoginFormRequest $request)
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
        $request->validated();
//        $this->validate($request, [
//            'email' => 'required|email',
//            'password' => 'required'
//        ]);
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Неверные данные'
                ]);
            }
            Auth::attempt($credentials, true);
            $user = Auth::user();
//            $user->perms = Role::find($user->roles[0]->id)->getPermissions();
            return response()->json([
                'token' => $token,
                'user' => new UserVuex($user)
            ], 200);
        } catch (JWTException $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function registration(RegistrationFormRequest $request)
    {
        $result = $this->create(UserFormRequest::createFrom($request));
        return $this->signin(LoginFormRequest::createFrom($request));
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json([
                'status' => 'success'
            ], 200);
        }
        catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }

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
    public function create(UserFormRequest $request)
    {
        $validation = $request->validated();
        try {
            $user = User::where([
                'email' => $request->email
            ])->count();
            if ($user) {
                throw new ControllerException('Такой пользователь существует в системе');
            }
            $user = new User();
            $user->fill([
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
                'last_name'     => $request->last_name,
                'first_name'    => $request->first_name,
                'middle_name'   => $request->middle_name,
                'birthday'      => $this->convertDate($request->birthday)
            ]);
            $user->save();
            $user_role = new RoleUser();
            $user_role->where('user_id', $user->id)
                ->delete();
            if (empty($request->role)) {
                $request->role = 1;
            }
            $user_role->fill([
                'role_id' => $request->role,
                'user_id' => $user->id
            ]);
            $user_role->save();
            return response()->json([
                'status' => 'success',
                'msg'    => 'Пользователь успешно добавлен'
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
            $users = User::paginate(10);
            return response()->json([
                'users' => new UserCollection($users)
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
                'msg'    => $error->getMessage()
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
            $user = User::with('role')->findOrFail($id);
            $role = $user->role[0]->id;
            unset($user->role);
            $user->role = $role;
            return response()->json([
                'user' => $user
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
            $roles = Role::all();
            return response()->json([
                'roles' => $roles
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function info()
    {
        try {
            if  (! Auth::user()) return abort(403);
            $user = Auth::user();
            $user->orders = Order::with('room')
                ->where('user_id', Auth::id())
                ->paginate(10);
            return response()->json([
             'user_info' => new UserInfo($user)
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
     * @param  \Illuminate\Http\Requests\User\UserFormRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, int $id)
    {
        try {
            $validation = $request->validated();
            $user = User::where([
                'email' => $request->email
            ])->count();
            if ($user > 1) {
                throw new \Exception('Такой пользователь существует в системе');
            }
            $user = User::findOrFail($id);
            $user->fill([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'birthday' => $this->convertDate($request->birthday)
            ]);
            $user->save();
            $user_role = new RoleUser();
            $user_role->where('user_id', $user->id)
                ->delete();
            $user_role->fill([
                'role_id' => $request->role,
                'user_id' => $user->id
            ]);
            $user_role->save();
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
