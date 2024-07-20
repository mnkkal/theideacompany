<table class="table table-responsive-sm table-striped">
    <thead>
        <tr>
            <th>{{ __('ID') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Description') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($parentGroups as $parentGroup)
            <tr>
                <td>{{ $parentGroup->id }}</td>
                <td>{{ $parentGroup->name }}</td>
                <td>{{ $parentGroup->description }}</td>
                <td>
                    
                    <a href="{{ route('parent-groups.edit', $parentGroup->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                    <form action="{{ route('parent-groups.destroy', $parentGroup->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Delete') }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
