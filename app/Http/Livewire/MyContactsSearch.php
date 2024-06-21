<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\View\View;
use Livewire\Component;

class MyContactsSearch extends Component
{
    public $users = [];

    public $myContactIds = [];

    public $blockedUsersIds = [];

    public $searchTerm;

    public $male;

    public $female;

    public $online;

    public $offline;

    public $myContactsCount;

    protected $listeners = [
        'clearSearchMyContacts' => 'clearSearchMyContacts',
        'addNewContactId' => 'addNewContactId',
        'addNewBlockedContactId' => 'addBlockedContactId',
        'removeBlockedContactId' => 'removeBlockedContactId',
    ];

    public function setMyContactIds(array $ids)
    {
        $this->myContactIds = $ids;
    }

    public function getMyContactIds(): array
    {
        return $this->myContactIds;
    }

    public function setBlockedUsersIds(array $ids)
    {
        $this->blockedUsersIds = $ids;
    }

    public function getBlockedUsersIds(): array
    {
        return $this->blockedUsersIds;
    }

    /**
     * it will initialize local variables
     */
    public function mount($myContactIds, $blockUserIds)
    {
        $this->setMyContactIds(array_unique($myContactIds));
        $this->setBlockedUsersIds($blockUserIds);
        $this->getMyContactsCount();
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $this->searchUsers();

        return view('livewire.my-contacts-search');
    }

    /**
     * clear search
     */
    public function clearSearchMyContacts()
    {
        $this->male = false;
        $this->female = false;
        $this->online = false;
        $this->offline = false;
        $this->searchTerm = '';

        $this->searchUsers();
    }

    /**
     * get user and apply filters
     */
    public function searchUsers()
    {
        $male = $this->male;
        $female = $this->female;
        $online = $this->online;
        $offline = $this->offline;
        if ($this->male && $this->female) {
            $male = false;
            $female = false;
        }
        if ($this->online && $this->offline) {
            $online = $offline = false;
        }
        if ($this->online && $this->offline && $this->male && $this->female) {
            $online = $offline = $male = $female = false;
        }
        $users = $this->getMyContactsQuery()
            ->when($male, function ($query) {
                return $query->where('gender', '=', User::MALE);
            })
            ->when($female, function ($query) {
                return $query->where('gender', '=', User::FEMALE);
            })
            ->when($online, function ($query) {
                return $query->where('is_online', '=', 1);
            })
            ->when($offline, function ($query) {
                return $query->where('is_online', '=', 0);
            })
            ->when($this->searchTerm, function ($query) {
                return $query->where(function ($q) {
                    $q->whereRaw('name LIKE ?', ['%'.strtolower($this->searchTerm).'%'])
                        ->orWhereRaw('email LIKE ?', ['%'.strtolower($this->searchTerm).'%']);
                });
            })
            ->select(['id', 'name', 'photo_url', 'is_online', 'gender', 'email'])
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get();

        $this->users = $users;
    }

    public function addNewContactId(int $userId)
    {
        $myContactIds = $this->getMyContactIds();
        array_push($myContactIds, $userId);
        $this->setMyContactIds($myContactIds);
        $this->getMyContactsCount();
    }

    public function addBlockedContactId(int $userId)
    {
        $blockedUsersIds = $this->getBlockedUsersIds();
        array_push($blockedUsersIds, $userId);
        $this->setBlockedUsersIds($blockedUsersIds);
        $this->getMyContactsCount();
    }

    public function removeBlockedContactId(int $userId)
    {
        $blockedUsersIds = $this->getBlockedUsersIds();
        if (($key = array_search($userId, $blockedUsersIds)) !== false) {
            unset($blockedUsersIds[$key]);
        }
        $this->setBlockedUsersIds($blockedUsersIds);
        $this->getMyContactsCount();
    }

    public function getMyContactsCount()
    {
        $this->myContactsCount = $this->getMyContactsQuery()->count();
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function getMyContactsQuery()
    {
        return User::with(['userStatus' => function (HasOne $query) {
            $query->whereNotIn('user_id', $this->getBlockedUsersIds());
        }])
            ->whereIn('id', $this->getMyContactIds())
            ->whereNotIn('id', $this->getBlockedUsersIds());
    }
}
