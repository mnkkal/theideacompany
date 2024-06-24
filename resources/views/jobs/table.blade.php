<table class="table  table-borderless table-responsive-sm table-responsive-lg table-responsive-md table-responsive-xl" id="jobs_table">
    <thead>
    <tr>
                <th>No</th>
                <th>Title</th>
                <th>Company</th>
                <th>Location</th>
                <th width="280px">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($jobs as $job)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company }}</td>
                    <td>{{ $job->location }}</td>
                    <td>
                        <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('jobs.show', $job->id) }}">Show</a>
                            <a class="btn btn-primary" href="{{ route('jobs.edit', $job->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
    </tbody>
</table>
