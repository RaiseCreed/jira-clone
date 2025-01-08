@section('statistics-customer')

<div>
    <div class="mb-2">
        <div class="mb-2 fs-5 text-center">Open tickets</div>
        <div class="row justify-content-center">
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-0">4</div>
                <div class="mb-2 text-nowrap">SLA-0</div>
            </div>
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-1">3</div>
                <div class="mb-2 text-nowrap">SLA-1</div>
            </div>
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-2">2</div>
                <div class="mb-2 text-nowrap">SLA-2</div>
            </div>
            <div class="nj-ticket-indicator">
                <div class="nj-ticket-circle sla-3">1</div>
                <div class="mb-2 text-nowrap">SLA-3</div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="mb-2 fs-5 text-center">Ticket due times</div>
            <div class="mb-2 nj-ticket-due-time-indicator">
                <div class="">Overdue</div>
                <div class="nj-ticket-circle overdue">4</div>
            </div>
            <div class="mb-2 nj-ticket-due-time-indicator">
                <div class="">Today</div>
                <div class="nj-ticket-circle today">4</div>
            </div>
            <div class="mb-2 nj-ticket-due-time-indicator">
                <div class="">Tomorrow</div>
                <div class="nj-ticket-circle tomorrow">4</div>
            </div>
            <div class="nj-ticket-due-time-indicator">
                <div class="">Later</div>
                <div class="nj-ticket-circle rest">4</div>
            </div>
        </div>
    </div>
</div>

@endsection