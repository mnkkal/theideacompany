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
        $permissions = Permission::toBase();

        return view('roles.create', compact('permissions'));
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
        if ($role->is_default) {
            return redirect()->back();
        }
        $permissions = Permission::toBase();

        return view('roles.edit', compact('permissions', 'role'));
    }

    /**
     * @throws Exception
     */
    public function update(Role $role, UpdateRoleRequest $request): RedirectResponse
    {
        if ($role->is_default) {
            Flash::error('You can not update default role.');

            return redirect()->back();
        }
        $this->roleRepository->updateRole($request->all(), $role);
        Flash::success(__('messages.new_keys.role_update'));

        return redirect()->route('roles.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        if ($role->is_default) {
            return $this->sendError(__('messages.new_keys.you_can_not_delete_default_role'));
        }
        if ($role->users->count() > 0) {
            return $this->sendError(__('messages.new_keys.role_is_already_assigned'));
        }
        $this->roleRepository->delete($role->id);

        return $this->sendSuccess(__('messages.new_keys.role_deleted'));
    }
}
