<table class="table  table-borderless table-responsive-sm table-responsive-lg table-responsive-md table-responsive-xl" id="jobs_table">
    <thead>
    <tr>
    <th>ID</th>
                    <th>Job Post</th>
                    <th>Email</th>
                    <th>Company Name</th>
                    <th>Job Type</th>
                    
                    <th width="130px">Apply By</th>
                 
                    <th>Hiring From</th>
         
                    <th width="280px">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($jobs as $job)
                <tr>
                <td>{{ $job->id }}</td>
                        <td>{{ $job->job_post }}</td>
                        <td>{{ $job->email }}</td>
                        <td>{{ $job->company_name }}</td>
                        <td>
                            @foreach(json_decode($job->job_type) as $type)
                                <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                            @endforeach
                        </td>
                       
                        <td>{{ $job->apply_by }}</td>
                      
                        <td>{{ $job->hiring_from }}</td>
                     
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
