<li class="nav-item {{ Request::is('dashboard*') ? 'active' : '' }}">
    <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ url('dashboard')  }}">
        <i class="fa fa-home nav-icon me-4"></i>
        <span>{{ __('Dashboard') }}</span>
    </a>
</li>
@can('manage_conversations')
<li class="nav-item {{ Request::is('conversations*') ? 'active' : '' }}">
    <a class="nav-link {{ Request::is('conversations*') ? 'active' : '' }}" href="{{ url('conversations')  }}">
        <i class="fa fa-commenting nav-icon me-4"></i>
        <span>{{ __('messages.conversations') }}</span>
    </a>
</li>
@endcan
@can('manage_parent_groups')
<li class="nav-item {{ Request::is('parent-groups*') ? 'active' : '' }}">
    <a class="nav-link {{ Request::is('parent-groups*') ? 'active' : '' }}" href="{{ url('parent-groups')  }}">
        <i class="fa fa-commenting nav-icon me-4"></i>
        <span>{{ __('Parent Groups') }}</span>
    </a>
</li>
@endcan

@can('manage_users')
    <li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="fa fa-users nav-icon me-4"></i>
            <span>{{ __('messages.users') }}</span>
        </a>
    </li>
@endcan
@can('manage_roles')
    <li class="nav-item {{ Request::is('roles*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="fa fa-user nav-icon me-4"></i>
            <span>{{ __('messages.roles') }}</span>
        </a>
    </li>
@endcan


<li class="nav-item {{ Request::is('products*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
        <i class="fa fa-tasks me-4" aria-hidden="true"></i>
            <span>{{ __('products') }}</span>
        </a>
    </li>
@can('manage-job')
    <li class="nav-item {{ Request::is('jobs*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('jobs*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
        <i class="fa fa-tasks me-4" aria-hidden="true"></i>
            <span>{{ __('Jobs') }}</span>
        </a>
    </li>
@else
<li class="nav-item {{ Request::is('joblist*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('joblist*') ? 'active' : '' }}" href="{{ route('joblist.index') }}">
        <i class="fa fa-tasks me-4" aria-hidden="true"></i>
            <span>{{ __('Jobs') }}</span>
        </a>
    </li>


@endcan
@can('manage-job-applications')
    <li class="nav-item {{ Request::is('job-applications*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('job-applications*') ? 'active' : '' }}" href="{{ route('job-applications.index') }}">
            <i class="fa fa-envelope-open nav-icon me-4"></i>
            <span>{{ __('Jobs Applications') }}</span>
        </a>
    </li>
@endcan
@can('manage-subcription')
    <li class="nav-item {{ Request::is('subscriptions*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('subscriptions*') ? 'active' : '' }}" href="{{ route('subscriptions.index') }}">
        <i class="fa fa-stack-exchange nav-icon me-4"></i>
            <span>{{ __('Subscriptions') }}</span>
        </a>
    </li>
@endcan
@can('manage_investors')

    <li class="nav-item {{ Request::is('investors*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('investors*') ? 'active' : '' }}" href="{{ route('investors.index') }}">
            <i class="fa fa-briefcase nav-icon me-4"></i>
            <span>{{ __('Form Of Pitch') }}</span>
        </a>
    </li>

@endcan
@can('manage_reported_users')
    <li class="nav-item {{ Request::is('reported-users*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('reported-users*') ? 'active' : '' }}"
           href="{{ route('reported-users.index') }}">
            <i class="fa fa-flag nav-icon me-4"></i>
            <span>{{ __('messages.reported_user') }}</span>
        </a>
    </li>
@endcan
@if( (Auth::user()->hasRole('Admin') || Auth::user()->hasPermissionTo('manage_meetings')))
    <li class="nav-item {{ Request::is('meetings*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('meetings*') ? 'active' : '' }}" href="{{ route('meetings.index') }}">
            <i class="fa fa-stack-exchange nav-icon me-4"></i>
            <span>{{ __('messages.meetings') }}</span>
        </a>
    </li>
@endif
@role('Member')
    <li class="nav-item {{ Request::is('meetings*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('meetings*') ? 'active' : '' }}" href="{{ route('meetings.member_index') }}">
            <i class="fa fa-stack-exchange nav-icon me-4"></i>
            <span>{{ __('messages.meetings') }}</span>
        </a>
    </li>
@endrole
@can('manage_front_cms')
<li class="nav-item {{ Request::is('front-cms*') ? 'active' : '' }}">
    <a class="nav-link {{ Request::is('front-cms*') ? 'active' : '' }}" href="{{ route('front.cms') }}">
        <i class="fa fa-home nav-icon me-4"></i>
        <span>{{ __('messages.new_keys.front_cms') }}</span>
    </a>
</li>
@endcan
@can('manage_front_cms')
<li class="nav-item {{ Request::is('transactions*') ? 'active' : '' }}">
    <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
        <i class="fa fa-home nav-icon me-4"></i>
        <span>{{ __('Transactions') }}</span>
    </a>
</li>
@endcan

@can('manage_settings')
    <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
            <i class="fa fa-gear nav-icon me-4"></i>
            <span>{{ __('messages.settings') }}</span>
        </a>
    </li>
@endcan
