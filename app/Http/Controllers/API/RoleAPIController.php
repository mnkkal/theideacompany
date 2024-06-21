<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Exception;
use Flash;
use Response;

class RoleAPIController extends AppBaseController
{
    /** @var RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    /**
     * Display a listing of the Role.
     */
    public function index(): Response
    {
        $roles = $this->roleRepository->all();

        return $this->sendResponse($roles, 'Roles retrieved successfully');
    }

    /**
     * Store a newly created Role in storage.
     */
    public function store(CreateRoleRequest $request): Response
    {
        $input = $request->all();

        $role = $this->roleRepository->create($input);
        $role = $role->refresh();

        return $this->sendResponse(['role' => $role], 'Role saved successfully.');
    }

    /**
     * Display the specified Role.
     */
    public function show(Role $role): Response
    {
        return $this->sendResponse($role, 'Role retrieved successfully');
    }

    /**
     * Update the specified Role in storage.
     */
    public function update(Role $role, UpdateRoleRequest $request): Response
    {
        if ($role->is_default) {
            Flash::success('You can not edit default role.');

            return redirect(route('roles.index'));
        }
        $this->roleRepository->update($request->all(), $role->id);

        $role = $role->refresh();

        return $this->sendResponse(['role' => $role], 'Role updated successfully.');
    }

    /**
     * Remove the specified Role from storage.
     *
     *
     * @throws Exception
     */
    public function destroy(Role $role): Response
    {
        if ($role->is_default) {
            Flash::success('You can not edit default role.');

            return redirect(route('roles.index'));
        }
        $this->roleRepository->delete($role->id);

        return $this->sendSuccess('Role deleted successfully.');
    }
}
