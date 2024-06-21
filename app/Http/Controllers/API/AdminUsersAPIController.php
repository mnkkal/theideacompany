<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Response;

/**
 * Class AdminUsersAPIController
 */
class AdminUsersAPIController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     *
     * @throws Exception
     */
    public function index(): Response
    {
        $users = User::with(['roles'])->orderBy('name', 'asc')->get()->except(getLoggedInUserId());
        foreach ($users as $key => $user) {
            /** @var User $user */
            $users[$key] = $user->apiObj();
        }

        return $this->sendResponse(['users' => $users], 'Users retrieved successfully.');
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateUserRequest $request): Response
    {
        $input = $this->validateInput($request->all());

        $user = $this->userRepository->store($input);
        $user->roles;
        $user = $user->apiObj();

        return $this->sendResponse(['user' => $user], 'User saved successfully.');
    }

    /**
     * Display the specified User.
     */
    public function show(User $user): Response
    {
        $user->roles;
        $user = $user->apiObj();

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     */
    public function update(User $user, UpdateUserRequest $request): Response
    {
        $input = $request->all();
        if (isset($input['password']) && ! empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $user = $this->userRepository->update($input, $user->id);
        $user = $user->apiObj();

        return $this->sendResponse(['user' => $user], 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(User $user): Response
    {
        $this->userRepository->delete($user->id);

        return $this->sendSuccess('User deleted successfully');
    }

    public function activeDeActiveUser(User $user): JsonResponse
    {
        $this->userRepository->activeDeActiveUser($user->id);

        return $this->sendSuccess('User updated successfully.');
    }

    public function validateInput($input)
    {
        if (! empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $input['is_active'] = (! empty($input['is_active'])) ? 1 : 0;

        return $input;
    }
}
