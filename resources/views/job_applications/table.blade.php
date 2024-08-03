<table class="table  table-borderless table-responsive-sm table-responsive-lg table-responsive-md table-responsive-xl" id="jobs_table">
    <thead>
    <tr>
    <th>No</th>
            <th>Job Post</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th width="280px">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($jobApplications as $application)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $application->job->job_post }}</td>
                <td>{{ $application->name }}</td>
                <td>{{ $application->email }}</td>
                <td>{{ $application->phone }}</td>
                <td>
                    <form action="{{ route('job-applications.destroy', $application->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('job-applications.show', $application->id) }}">Show</a>
                        <a class="btn btn-primary" href="{{ route('job-applications.edit', $application->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
