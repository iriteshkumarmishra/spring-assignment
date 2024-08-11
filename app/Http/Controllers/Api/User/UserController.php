<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserPointsRequest;
use App\Services\UserService;
use App\Jobs\GenerateQrCodeJob;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['age', 'points', 'name', 'sort_key', 'sort_order']);
        $users = $this->userService->listUsers($filters);

        return response()->json($users);
    }

    /**
     * Store a newly created user in the database.
     *
     * @param StoreUserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        if (!$user) {
            return response()->json(['message' => 'User creation Failed'], Response::HTTP_BAD_REQUEST);
        }
        // Dispatch the QR code generation job for user's address
        GenerateQrCodeJob::dispatch($user);

        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $response['user'] = $user;
        $response['address'] = $user->address->fullAddress();

        return response()->json($response);
    }

    /**
     * Add or remove points from a user.
     *
     * @param UpdateUserPointsRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePoints(UpdateUserPointsRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->operation === 'add') {
            $updatedUser = $this->userService->addPoints($user, $request->points);
        } else {
            $updatedUser = $this->userService->removePoints($user, $request->points);
        }

        return response()->json([
            'message' => 'Points updated successfully.',
            'user' => $updatedUser,
        ]);
    }

    /**
     * Remove the specified user from the database.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Get users grouped by score with average age.
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function getUsersGroupedByScore(Request $request)
    {
        $data = $this->userService->getUsersGroupedByScore();

        return response()->json($data, Response::HTTP_OK);
    }
}