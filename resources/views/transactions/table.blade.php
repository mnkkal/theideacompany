<table class="table table-responsive-sm table-striped table-bordered">
    <thead>
        <tr>
            <th>{{ __('ID') }}</th>
            <th>{{ __('User') }}</th>
            <th>{{ __('Subscription Plan') }}</th>
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Processed') }}</th>
            <th>{{ __('Created At') }}</th>
            <th>{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ optional($transaction->subscriptionPlan)->name }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ $transaction->processed ? 'Yes' : 'No' }}</td>
                <td>{{ $transaction->created_at }}</td>
                <td>
                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
