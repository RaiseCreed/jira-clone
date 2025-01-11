@section('statistics-admin')

<div>
    <div class="mb-2 fs-5 fw-semibold text-center">Team workload:</div>
    @foreach ($workers as $worker)
    <div class="align-items-center mb-3">
        @php
        switch (true) {
        case $worker->workload_percentage <= 20: $i='green' ; break; case $worker->workload_percentage <= 40:
                $i='yellowgreen' ; break; case $worker->workload_percentage <= 60: $i='yellow' ; break; case $worker->
                    workload_percentage <= 80: $i='orange' ; break; default: $i='red' ; break; } @endphp <label
                        class="me-2">{{ $worker->name }}:</label>
                        <div class="w-100 progress" role="progressbar" aria-label="Success example"
                            aria-valuenow="{{ $worker->workload_percentage }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="nj-progress-bar-{{ $i }}" style="width: {{ $worker->workload_percentage }}%">{{
                                $worker->workload_percentage }}%</div>
                        </div>
    </div>
    @endforeach

</div>
@endsection