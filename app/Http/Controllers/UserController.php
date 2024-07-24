<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Queries\UserDataTable;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use DataTables;
use Exception;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Response;
use Spatie\Permission\Models\Permission;

class UserController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * @return Factory|View
     */
    public function getProfile(): View
    {
        return view('profile');
    }

    /**
     * Display a listing of the User.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|Response
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new UserDataTable())->get($request->only(['filter_user', 'privacy_filter'])))->make(true);
        }
        $roles = Role::pluck('name', 'id')->toArray();
        
       
        $permissions = Permission::all();
        return view('users.index')->with([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        $roles = Role::all()->pluck('name', 'id')->toArray();

        return view('users.create')->with(['roles' => $roles]);
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $input = $this->validateInput($request->all());

        $this->userRepository->store($input);

        return $this->sendSuccess(__('messages.new_keys.user_saved_sucessfully'));
    }

    /**
     * Display the specified User.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user): View
    {
        $user->roles;
        $user = $user->apiObj();

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit(User $user): JsonResponse
    {
       
        $user = User::with(['roles', 'permissions'])->findOrFail($user->id);
        $user = $user->apiObj();
        $permissions = Permission::all();
        return $this->sendResponse(['user' => $user, 'permissions'=> $permissions], 'User retrieved successfully.');
    }

    /**
     * Update the specified User in storage.
     */
    public function update(User $user, UpdateUserRequest $request): JsonResponse
    {
        if (! empty($user->is_system)) {
            return $this->sendError(__('messages.new_keys.you_can_not_update_system_generated_user'));
        }

        $input = $this->validateInput($request->all());
        $this->userRepository->update($input, $user->id);

        return $this->sendSuccess(__('messages.new_keys.user_updated_successfully'));
    }

    public function updateLanguage(Request $request): JsonResponse
    {
        $language = $request->get('languageName');

        $user = getLoggedInUser();
        $user->update(['language' => $language]);

        return $this->sendSuccess('Language updated successfully.');
    }

    /**
     * Remove the specified User from storage.
     *
     *
     * @throws Exception
     */
    public function archiveUser(User $user): JsonResponse
    {
        if (! empty($user->is_system)) {
            return $this->sendError('You can not archive system generated user.');
        }

        $this->userRepository->delete($user->id);

        return $this->sendSuccess(__('messages.new_keys.user_archived_successfully'));
    }

    /**
     * Remove the specified User from storage.
     */
    public function restoreUser(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $this->userRepository->restore($id);

        return $this->sendSuccess(__('messages.new_keys.user_restored_successfully'));
    }

    /**
     * Remove the specified User from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::withTrashed()->whereId($id)->first();

        if (empty($user)) {
            return $this->sendError(__('messages.new_keys.user_not_found'));
        }

        if (! empty($user->is_system)) {
            return $this->sendError(__('messages.new_keys.you_can_not_delete_system_generated_user'));
        }

        $this->userRepository->deleteUser($user->id);

        return $this->sendSuccess(__('messages.new_keys.user_deleted_successfully'));
    }

    public function activeDeActiveUser(User $user): JsonResponse
    {
        $this->userRepository->checkUserItSelf($user->id);
        $this->userRepository->activeDeActiveUser($user->id);

        return $this->sendSuccess(__('messages.new_keys.user_status'));
    }

    /**
     * @return mixed
     */
    public function validateInput($input)
    {
        if (isset($input['password']) && ! empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $input['is_active'] = (! empty($input['is_active'])) ? 1 : 0;

        return $input;
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function userImpersonateLogin(User $user): RedirectResponse
    {
        Auth::user()->impersonate($user);

        if (\Auth::check() && \Auth::user()->hasPermissionTo('manage_conversations')) {
            return redirect(url('/conversations'));
        } elseif (\Auth::check()) {
            if (\Auth::user()->getAllPermissions()->count() > 0) {
                $url = getPermissionWiseRedirectTo(\Auth::user()->getAllPermissions()->first());

                return redirect(url($url));
            } else {
                return redirect(url('/conversations'));
            }
        }
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function userImpersonateLogout(): RedirectResponse
    {
        Auth::user()->leaveImpersonation();

        return redirect(url('/conversations'));
    }

    public function isEmailVerified(User $user): JsonResponse
    {
        $emailVerified = $user->email_verified_at == null ? Carbon::now() : null;
        $user->update(['email_verified_at' => $emailVerified]);

        return $this->sendSuccess(__('messages.new_keys.email_verified'));
    }
}
