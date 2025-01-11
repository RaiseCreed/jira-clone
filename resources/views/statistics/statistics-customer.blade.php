@section('statistics-customer')

<div>
    <div class="mb-2">
        <div class="mb-2 fs-5 fw-semibold text-center">Open tickets</div>
        <div class="row justify-content-center">
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-0">{{$ticketsByPriority['SLA-0']}}</div>
                <div class="mb-2 text-nowrap">SLA-0</div>
            </div>
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-1">{{$ticketsByPriority['SLA-1']}}</div>
                <div class="mb-2 text-nowrap">SLA-1</div>
            </div>
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-2">{{$ticketsByPriority['SLA-2']}}</div>
                <div class="mb-2 text-nowrap">SLA-2</div>
            </div>
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-3">{{$ticketsByPriority['SLA-3']}}</div>
                <div class="mb-2 text-nowrap">SLA-3</div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="mb-2 fs-5 fw-semibold text-center">Ticket due times</div>
            <div class="mb-2 nj-ticket-due-time-indicator">
                <div class="">Overdue</div>
                <div class="nj-ticket-circle overdue">{{$ticketsByDueTime['overdue']}}</div>
            </div>
            <div class="mb-2 nj-ticket-due-time-indicator">
                <div class="">Today</div>
                <div class="nj-ticket-circle today">{{$ticketsByDueTime['today']}}</div>
            </div>
            <div class="mb-2 nj-ticket-due-time-indicator">
                <div class="">Tomorrow</div>
                <div class="nj-ticket-circle tomorrow">{{$ticketsByDueTime['tomorrow']}}</div>
            </div>
            <div class="nj-ticket-due-time-indicator">
                <div class="">Later</div>
                <div class="nj-ticket-circle rest">{{$ticketsByDueTime['later']}}</div>
            </div>
        </div>
    </div>
</div>

@endsection