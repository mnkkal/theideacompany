<table class="table  table-borderless table-responsive-sm table-responsive-lg table-responsive-md table-responsive-xl" id="jobs_table">
    <thead>
    <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                   
                <th width="280px">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->name }}</td>
                        <td>{{ $subscription->description }}</td>
                        <td>{{ $subscription->price }}</td>
                        <td>
                            <a href="{{ route('subscriptions.edit', $subscription->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
    </tbody>
</table>
