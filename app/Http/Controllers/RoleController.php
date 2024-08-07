<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Queries\RoleDataTable;
use App\Repositories\RoleRepository;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class RoleController extends AppBaseController
{
    /** @var RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    /**
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new RoleDataTable())->get())->make(true);
        }

        return view('roles.index');
    }

    /**
     * @return Factory|View
     */
    public function create(): View
    {
        if(Auth::user()->hasPermissionTo('manage_roles')){
            $permissions = Permission::toBase();

            return view('roles.create', compact('permissions'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }

    /**
     * @throws Exception
     */
    public function store(CreateRoleRequest $request): RedirectResponse
    {
        $input = $request->all();
        $this->roleRepository->storeRole($input);
        Flash::success(__('messages.new_keys.role_saved_successfully'));

        return redirect()->route('roles.index');
      
    }

    /**
     * @return Application|Factory|View
     */
    public function show(Role $role)
    {
        return redirect()->back();
        //        return \view('roles.show',compact('role'));
    }

    /**
     * @return Application|Factory|View
     */
    public function edit(Role $role)
    {
        
        if(Auth::user()->hasPermissionTo('manage_roles')){
       
        $permissions = Permission::toBase();

        return view('roles.edit', compact('permissions', 'role'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, $roleId)
    {
        if (Auth::user()->hasPermissionTo('manage_roles')) {
        // Find the role by its ID
            $role = Role::find($roleId);

            if (!$role) {
                return redirect()->back()->with(
                    'error', 'Role not found.'
                );
            }

            // Update the role's name
            $role->name = $request->input('name');

            // Save the updated role
            $role->save();

            // Sync the role's permissions
            $role->syncPermissions($request->input('permissions'));
            

        // Sync all users with this role
            $users = User::role($role->name)->get();

            foreach ($users as $user) {
                $user->syncPermissions($request->input('permissions'));
            }
            Flash::success(__('Role updated successfully'));
            // Redirect back with a success message
            return redirect()->route('roles.index')->with(
                'success', 'Role updated successfully'
            );
        } else {
            // Redirect back with an error message if the user does not have permission
            return redirect()->back()->with(
                'error', 'Sorry! You do not have permission to access this page!'
            );
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        if(Auth::user()->hasPermissionTo('manage_roles')){
            if ($role->is_default) {
                return $this->sendError(__('messages.new_keys.you_can_not_delete_default_role'));
            }
            if ($role->users->count() > 0) {
                return $this->sendError(__('messages.new_keys.role_is_already_assigned'));
            }
            $this->roleRepository->delete($role->id);

            return $this->sendSuccess(__('messages.new_keys.role_deleted'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }
}
