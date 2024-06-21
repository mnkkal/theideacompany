<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateGroupRequest;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Repositories\GroupRepository;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupAPIController extends AppBaseController
{
    /** @var GroupRepository */
    private $groupRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepository = $groupRepo;
    }

    public function index(Request $request): JsonResponse
    {
        $groups = $this->groupRepository->all();

        $groups = $groups->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'photo_url' => $group->photo_url,
            ];
        });

        return $this->sendResponse($groups->toArray(), 'Groups retrieved successfully.');
    }

    public function show(Group $group, Request $request): JsonResponse
    {
        $users = $group->users->pluck('id')->toArray();
        $group = $group->toArray();
        $group['users'] = $users;

        return $this->sendResponse($group, 'Group retrieved successfully.');
    }

    public function create(CreateGroupRequest $request): JsonResponse
    {
        if (! Auth::user()->hasRole('Admin') && ! canMemberAddGroup()) {
            return $this->sendError('Sorry, you can not create group.');
        }

        $input = $request->all();
        $input['group_type'] = ($input['group_type'] == '1') ? Group::TYPE_OPEN : Group::TYPE_CLOSE;
        $input['privacy'] = ($input['privacy'] == '1') ? Group::PRIVACY_PUBLIC : Group::PRIVACY_PRIVATE;
        $input['created_by'] = getLoggedInUserId();

        $group = $this->groupRepository->store($input);
        $group->append('group_created_by');

        return $this->sendResponse($group, __('messages.new_keys.group_created'));
    }

    public function update(Group $group, Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'mimes:png,jpeg,jpg',
        ]);

        if (! Auth::user()->hasRole('Admin') && ! canMemberAddGroup()) {
            return $this->sendError('Sorry, you can not create group.');
        }

        $input = $request->all();
        unset($input['users']);

        if ($group->my_role === GroupUser::ROLE_ADMIN) {
            $input['group_type'] = ($input['group_type'] == '1') ? Group::TYPE_OPEN : Group::TYPE_CLOSE;
            $input['privacy'] = ($input['privacy'] == '1') ? Group::PRIVACY_PUBLIC : Group::PRIVACY_PRIVATE;
        } else {
            unset($input['group_type']);
            unset($input['privacy']);
        }

        [$group, $conversation] = $this->groupRepository->update($input, $group->id);

        return $this->sendResponse(
            ['group' => $group->toArray(), 'conversation' => $conversation], __('messages.new_keys.group_details_updated')
        );
    }

    /**
     * @throws Exception
     */
    public function addMembers(Group $group, Request $request): JsonResponse
    {
        if ($group->privacy == Group::PRIVACY_PRIVATE && ! $this->groupRepository->isAuthUserGroupAdmin($group->id)) {
            return $this->sendError('Only admin user can add members to the group');
        }
        $users = $request->get('members');

        /** @var User $addedMembers */
        [$addedMembers, $conversation] = $this->groupRepository->addMembersToGroup($group, $users);
        $group = $group->toArray();
        $group['users'] = $addedMembers;

        return $this->sendResponse(['group' => $group, 'conversation' => $conversation], __('messages.new_keys.member_added'));
    }

    /**
     * @throws Exception
     */
    public function removeMemberFromGroup(Group $group, User $user): JsonResponse
    {
        $conversation = $this->groupRepository->removeMemberFromGroup($group, $user);

        return $this->sendResponse($conversation, __('messages.new_keys.member_removed'));
    }

    /**
     * @throws Exception
     */
    public function leaveGroup(Group $group): JsonResponse
    {
        $group->users;
        $conversation = $this->groupRepository->leaveGroup($group, Auth::id());

        return $this->sendResponse($conversation, __('messages.new_keys.leave_this_group'));
    }

    /**
     * @throws Exception
     */
    public function removeGroup(Group $group): JsonResponse
    {
        $this->groupRepository->removeGroup($group, Auth::id());

        return $this->sendSuccess(__('messages.new_keys.you_are_deleted_group'));
    }

    public function makeAdmin(Group $group, User $user): JsonResponse
    {
        $conversation = $this->groupRepository->makeMemberToGroupAdmin($group, $user);

        return $this->sendResponse($conversation, $user->name.' '. __('messages.new_keys.add_admin_role'));
    }

    public function dismissAsAdmin(Group $group, User $user): JsonResponse
    {
        $conversation = $this->groupRepository->dismissAsAdmin($group, $user);

        return $this->sendResponse($conversation, $user->name.' '.__('messages.new_keys.remove_admin_role'));
    }
}
