@section('statistics-admin')

<div>
    Team workload:
    <table class="table">
        <tbody>
            @foreach ($workers as $worker)
            <p>{{ $worker->name }}: {{ $worker->workload_percentage }}%</p>
            @endforeach
        </tbody>
    </table>
</div>
@endsection