<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Skor;
use Ramsey\Uuid\Uuid;
use App\Models\Kontrol;
use App\Models\UserOld;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $level = Auth::user()->level;
        $IDprsh = Auth::user()->idprsh;
        $IDopr = Auth::user()->idopr;
        $IDuser = Auth::user()->idx;

        $qryUser = UserOld::from('user as a');
        if ($level == 'administrator' and $IDprsh == 0) {
            $queryUser = $qryUser;
        } else if ($level == 'administrator' and $IDprsh > 0) {
            $queryUser = $qryUser->where('idprsh', $IDprsh);
        } else {
            $queryUser = $qryUser->where('idprsh', $IDprsh)->where('idopr', $IDopr);
        }

        $data['user'] = $queryUser->orderByDesc('idx')->limit(10)->get();
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'nama' => 'required',
            'username' => 'required|string|min:4|unique:user,username',
            'password' => 'required',
        ]);

        $akses = '1,3,102,110,111,6';
        if ($request->level == 'administrator') {
            $akses = '1,2,8,15,3,102,101,110,112,111,6';
        }
        $jamNow = date('Y-m-d H:i:s');

        if (isset($request->idopr)) {
            $opr = Customer::where('id', $request->idopr)->first();
        }
        $data = UserOld::create([
            'idprsh' => Auth::user()->idprsh,
            'idopr' => $request->idopr,
            'username' => $request->username,
            'password' => substr(md5($request->password), 0, 20),
            'password_new' => Hash::make($request->password),
            'nama' => $request->idopr ? $opr->nama : $request->nama,
            'level' => $request->level,
            'status' => $request->status,
            'jam' => $jamNow,
            'akses' => $akses
        ]);
        if ($data) {
            DB::commit();
            return response()->json([
                'message' => 'User created successfully',
                'user' => $data,
            ], 201);
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'User created failed',
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserOld $user)
    {
        return response()->json([
            'message' => 'success',
            'data' => $user,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserOld $user)
    {
        DB::beginTransaction();
        $request->validate([
            'nama' => 'required',
            // 'username' => 'required|string|min:4|unique:user,username',
            'username' => [
                'required',
                'string',
                'min:4',
                Rule::unique('user', 'username')->ignore($user->idx, 'idx'),
            ],
            'password' => 'required',
        ]);

        $akses = '1,3,102,110,111,6';
        if ($request->level == 'administrator') {
            $akses = '1,2,8,15,3,102,101,110,112,111,6';
        }

        $opr = Customer::where('id', $request->idopr)->first();
        $user->update([
            'idopr' => $request->idopr ? $request->idopr : $user->idopr,
            'username' => $request->username,
            'password' => substr(md5($request->password), 0, 20),
            'password_new' => Hash::make($request->password),
            'nama' => $request->idopr ? $opr->nama : $request->nama,
            'level' => $request->level ? $request->level : $user->level,
            'status' => $request->status ? $request->status : $user->status,
            'akses' => $akses,
            'q1' => $request->q1 ? $request->q1 : $user->q1,
            'q2' => $request->q2 ? $request->q2 : $user->q2,
            'a1' => $request->a1 ? $request->a1 : $user->a1,
            'a2' => $request->a2 ? $request->a2 : $user->a2
        ]);
        DB::commit();
        return response()->json([
            'message' => 'Data updated successfully',
            'user' => $user,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserOld $user)
    {
        $cek = Skor::where('iduser', $user->idx)->count();
        $cek2 = Kontrol::where('iduser', $user->idx)->count();
        if ($cek == 0 && $cek2 == 0) {
            DB::table('user')->where('idx', $user->idx)->delete();
            return response()->json([
                'message' => 'Data deleted successfully',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Data gagal dihapus, sudah digunakan di database lain!',
            ], 201);
        }
    }
}
