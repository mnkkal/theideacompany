<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SearchUsers extends Component
{
    public $users = [];

    public $myContactIds = [];

    public $searchTerm;

    public $male;

    public $female;

    protected $listeners = ['clearSearchUsers' => 'clearSearchUsers'];

    public function setMyContactIds(array $ids)
    {
        $this->myContactIds = $ids;
    }

    public function getMyContactIds(): array
    {
        return $this->myContactIds;
    }

    /**
     * initialize variables
     */
    public function mount($myContactIds, $blockUserIds)
    {
        $userIds = array_unique(array_merge($blockUserIds, array_keys($blockUserIds)));
        $userIds = array_unique(array_merge($userIds, $myContactIds));
        $this->setMyContactIds($userIds);
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $this->searchUsers();

        return view('livewire.search-users');
    }

    public function clearSearchUsers()
    {
        $this->male = false;
        $this->female = false;
        $this->searchTerm = '';

        $this->searchUsers();
    }

    /**
     * search users and apply filters
     */
    public function searchUsers()
    {
        $loggedInUser = Auth::user(); 
        $isAdmin = $loggedInUser->hasRole('Admin'); 
    
        // Initialize gender filters
        $male = $this->male;
        $female = $this->female;
    
       
        if ($this->male && $this->female) {
            $male = false;
            $female = false;
        }
    
        // Prepare the base query
        $users = User::whereNotIn('id', $this->getMyContactIds())
            ->when($isAdmin, function ($query) use ($male, $female) {
                // If the user is an admin, apply gender and search filters
                return $query->when($male, function ($query) {
                    return $query->where('gender', '=', User::MALE);
                })
                ->when($female, function ($query) {
                    return $query->where('gender', '=', User::FEMALE);
                })
                ->when($this->searchTerm, function ($query) {
                    return $query->where(function ($q) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->searchTerm) . '%'])
                            ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($this->searchTerm) . '%']);
                    });
                });
            }, function ($query) {
               
                return $query->role('admin');
            })
            ->orderBy('name', 'asc')
            ->select(['id', 'is_online', 'gender', 'photo_url', 'name', 'email'])
            ->limit(20)
            ->get()
            ->except(getLoggedInUserId());
    
        // Assign the result to the class property
        $this->users = $users;
    }
}
