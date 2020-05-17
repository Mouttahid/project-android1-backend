<?php

namespace App\Http\Controllers;

use App\Notifications\AccountCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function createUser(Request $request){
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'role' => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'errors', 'msg' => $validator->getMessageBag()], 200);
        } else {
            $password = Str::random(10);
            $fillables = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ];
            if ($request->has("chef")) {
                $fillables["chef_id"] = $request->chef;
                dd($fillables);
            }
            $user = null;
            if ($user = User::create($fillables)) {
                if ($user->assignRole($request->role)) {
                    $user->notify(new AccountCreatedNotification($password));
                    return response()->json([
                        'success' => true,
                        'msg' => 'user created successfully'
                    ], 201);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'user creation failed'
                ], 201);
            }
        }
    }

    public function signup(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|confirmed|unique:users',
            'password' => 'required|string|confirmed',
            'role' => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'errors', 'msg' => $validator->getMessageBag()], 200);
        } else {
            $fillables = [
                'fullname' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ];
            if ($request->has("chef")) {
                $fillables["chef_id"] = $request->chef;
            }
            $user = User::create($fillables);
            if ($user->save()) {
                if ($user->assignRole($request->role)) {
                    return response()->json([
                        'success' => true,
                        'msg' => 'user created successfully'
                    ], 201);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'user creation failed'
                ], 201);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'errors', 'msg' => $validator->getMessageBag()], 200);
        } else {
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    "success" => false,
                    "type" => "Unauthorized",
                    'message' => 'Unauthorized'
                ], 201);
            }

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();
            return response()->json([
                'success' => true,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                "user" => $request->user(),
                "role" => $request->user()->getRoleNames()[0]
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            "success" => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function checkUserRole(Request $request)
    {
        return response()->json($request->user()->getRoleNames());
    }
    public function UserWithRoles(Request $request)
    {
        return response()->json(["success" => true, $request->user(),$request->user()->getRoleNames()]);
    }
    public function verifyAuth(Request $request)
    {
        $user = null;
        if ($user = $request->user()) {
            $user->getRoleNames();
            $user["role"] = $user['roles'][0]->name;
            unset($user["roles"]);
            return response()->json(["success" => true, "user" => $user]);
        }
        return response()->json(["success" => false]);
    }

    public function asignUsertoTeam(Request $request)
    {
        $user = User::find($request->id);
        $user->chef_id = $request->chef;
        if ($user->save()) {
            return response()->json([
                "success" => true,
            ]);
        } else {
            return response()->json([
                "success" => false
            ]);
        }
    }

    public function getChiefs(Request $request){
        return response()->json(["success"=>true,"chiefs" =>  User::getChiefs()]);
    }
}