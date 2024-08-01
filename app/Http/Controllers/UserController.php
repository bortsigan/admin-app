<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ClientInterest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use DB;

class UserController extends Controller
{
    private $user;
    private $clientInterest;

    public function __construct(User $user, ClientInterest $clientInterest)
    {
        $this->user = $user;
        $this->clientInterest = $clientInterest;
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getMyUsers(Int $userId, Request $request)
    { #As an admin, I can only see clients that belong to me.
        $roleId = $request->get('role_id') ?? Role::ROLE_CLIENT;
        $users = $this->user->where('user_id', $userId);

        if ($roleId) {
            $users->where('role_id', $roleId);
        }

        $users = $users->get();
        $list = $users->isNotEmpty() ? $users : [];

        return response()->json($list, 200);
    }

    public function getMyInterests(Int $userId)
    {
        $interests = $this->clientInterest->where('user_id', $userId)->orderByDesc('created_at')->get();
        $result = [];

        foreach ($interests as $interest) {

            if (isset($result[$interest->interest_id])) {
                continue;
            }

            $result[$interest->interest_id] = [
                'id' => $interest->id,
                'user_id' => $interest->user_id,
                'interest_id' => $interest->interest_id,
                'interest_name' => $interest->interest->name
            ];
        }

        return response()->json($result, 200);
    }

    public function getMyClientDetails(Int $userId, Int $clientId)
    { 
        $client = $this->user
                        ->where('id', $clientId)
                        ->where('user_id', $userId)
                        ->where('role_id', Role::ROLE_CLIENT)
                        ->with(['clientInterests:id,interest_id,user_id'])
                        ->first();
        
        return response()->json($client ?? null, 200);
    }

    public function deleteMyInterests(Int $userId, Request $request)
    {
        try {
            $ids = $request->get('client_interest_ids');
            $this->clientInterest->whereIn('id', $ids)->where('user_id', $userId)->delete();

            return response()->json(true, 204);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 403);
        }
    }
    

    public function addInterests(Int $userId, Request $request)
    {
        $ids = $request->get('interest_ids');
        if (empty($ids)) {
            return response()->json(['error' => true, 'message' => 'Nothing to insert'], 403);
        }

        try {

            DB::beginTransaction();
            $datas = [];

            foreach($ids as $id) {
                $datas[] = [
                    'user_id' => $userId,
                    'interest_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $insertedDatas = $this->clientInterest->insert($datas);

            DB::commit();

            return response()->json($insertedDatas, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()], 403);
        }
    }

    public function updateMyClientDetail(int $adminId, int $clientId, StoreUserRequest $request)
    { # As an admin, I can edit my client’s detail
        try {
            DB::beginTransaction();

            $client = $this->user->where('user_id', $adminId)->where('id', $clientId)->first();

            if (!$client) {
                return response()->json(['error' => true, 'message' => 'You are not allowed to update this client.'], 403);
            }

            $user = $this->user->findOrFail($clientId);
            $validated = $request->validated();
            $interests = [];

            $update = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'birthday' => $validated['birthday'],
                'contact_no' => $validated['contact_no'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $update['password'] = Hash::make($validated['password']);
            }

            $user->update($update);

            $user->clientInterests()->delete();

            foreach ($validated['interest_ids'] as $interestId) {
                $interests[] = [
                    'user_id' => $user->id,
                    'interest_id' => $interestId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->clientInterest->insert($interests);

            DB::commit();

            return response()->json(['message' => 'User updated successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function createMyClient(int $adminId, StoreUserRequest $request)
    { # As an admin, I can register my clients and add their interests
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $interests = [];

            $data = [
                'role_id' => Role::ROLE_CLIENT,
                'user_id' => $adminId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'birthday' => $validated['birthday'],
                'contact_no' => $validated['contact_no'],
                'password' => Hash::make($validated['password']),
                'email' => $validated['email'],
            ];

            $user = $this->user->create($data);

            foreach ($validated['interest_ids'] as $interestId) {
                $interests[] = [
                    'user_id' => $user->id,
                    'interest_id' => $interestId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->clientInterest->insert($interests);

            DB::commit();

            return response()->json(['message' => 'Client added successfully', 'user' => $user], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }


    public function deleteMyClientDetail(int $adminId, int $clientId)
    { # As an admin, I can delete my clients

        try {
            DB::beginTransaction();

            $client = $this->user->where('user_id', $adminId)->where('id', $clientId)->first();

            if (!$client) {
                return response()->json(['error' => true, 'message' => 'You are not allowed to update this client.'], 403);
            }

            $user = $this->user->findOrFail($clientId);
            $user->clientInterests()->delete(); # delete client interest 
            $user->delete(); # delete client

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }
}