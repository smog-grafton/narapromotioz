@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="My Tickets" 
    subtitle="Manage and view all your purchased tickets"
    :breadcrumbs="[
        ['title' => 'Dashboard', 'url' => route('dashboard')],
        ['title' => 'My Tickets']
    ]" />

<div class="tickets-container">
    <div class="container">
        <!-- Tickets Header -->
        <div class="tickets-header">
            <div class="tickets-info">
                <h1 class="tickets-title">Ticket Management</h1>
                <p class="tickets-subtitle">View, download, and manage your boxing event tickets</p>
            </div>
            <div class="tickets-actions">
                <a href="{{ route('events.index') }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                    Browse Events
                </a>
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        <!-- Tickets Stats -->
        <div class="tickets-stats">
            <div class="stat-card">
                <div class="stat-icon total">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $userTickets->count() }}</div>
                <div class="stat-label">Total Tickets</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon upcoming">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $userTickets->where('status', 'confirmed')->count() }}</div>
                <div class="stat-label">Confirmed</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon used">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $userTickets->where('status', 'used')->count() }}</div>
                <div class="stat-label">Used</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon expired">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $userTickets->where('status', 'cancelled')->count() }}</div>
                <div class="stat-label">Cancelled</div>
            </div>
        </div>

        <!-- Tickets Filter -->
        <div class="tickets-filters">
            <div class="filters-row">
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="used">Used</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Event</label>
                    <input type="text" class="filter-input" placeholder="Search by event name..." id="eventFilter">
                </div>

                <div class="filter-actions">
                    <button class="btn-filter" onclick="applyFilters()">Filter</button>
                    <button class="btn-clear" onclick="clearFilters()">Clear</button>
                </div>
            </div>
        </div>

        <!-- Tickets Grid -->
        @if($userTickets->count() > 0)
            <div class="tickets-grid">
                @foreach($userTickets as $purchase)
                    <div class="ticket-card" data-status="{{ $purchase->status }}" data-event="{{ strtolower($purchase->ticket->event->name ?? 'unknown') }}">
                        <!-- Status Badge -->
                        <div class="ticket-status">
                            <span class="status-badge {{ strtolower($purchase->status) }}">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </div>

                        <!-- Event Image -->
                        @if($purchase->ticket->event && $purchase->ticket->event->poster_image)
                            <img src="{{ asset('storage/' . $purchase->ticket->event->poster_image) }}" 
                                 alt="{{ $purchase->ticket->event->name }}" 
                                 class="ticket-event-image">
                        @else
                            <div class="ticket-event-image placeholder">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Ticket Content -->
                        <div class="ticket-content">
                            <h3 class="ticket-event-title">{{ $purchase->ticket->event->name ?? 'Unknown Event' }}</h3>

                            <div class="ticket-details">
                                <div class="ticket-detail">
                                    <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                    </svg>
                                    <span class="detail-label">Date:</span>
                                    <span class="detail-value">
                                        {{ $purchase->ticket->event ? $purchase->ticket->event->event_date->format('M j, Y') : 'TBD' }}
                                    </span>
                                </div>

                                <div class="ticket-detail">
                                    <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                    </svg>
                                    <span class="detail-label">Venue:</span>
                                    <span class="detail-value">
                                        {{ $purchase->ticket->event->venue ?? 'TBD' }}
                                    </span>
                                </div>

                                <div class="ticket-detail">
                                    <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                                    </svg>
                                    <span class="detail-label">Qty:</span>
                                    <span class="detail-value">{{ $purchase->quantity }} ticket(s)</span>
                                </div>

                                <div class="ticket-detail">
                                    <svg class="detail-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                                    </svg>
                                    <span class="detail-label">Total:</span>
                                    <span class="detail-value">${{ number_format($purchase->total_price, 2) }}</span>
                                </div>
                            </div>

                            <!-- Reference Number -->
                            <div class="ticket-reference">
                                <div class="reference-label">Reference Number</div>
                                <div class="reference-value">{{ $purchase->order_number }}</div>
                            </div>

                            <!-- Actions -->
                            <div class="ticket-actions">
                                @if($purchase->status === 'confirmed')
                                    <a href="{{ route('tickets.download', $purchase->order_number) }}" class="btn btn-primary">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M5 20h14v-2H5v2zM19 9h-4V3H9v6H5l7 7 7-7z"/>
                                        </svg>
                                        Download
                                    </a>
                                @endif

                                <a href="{{ route('events.show', $purchase->ticket->event->slug ?? '#') }}" class="btn btn-secondary">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    View
                                </a>

                                @if($purchase->status === 'confirmed' && $purchase->ticket->event && $purchase->ticket->event->event_date > now())
                                    <a href="{{ route('events.show', $purchase->ticket->event->slug) }}" class="btn btn-success">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                        </svg>
                                        Event Info
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="tickets-empty">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                </svg>
                <h3 class="empty-title">No Tickets Found</h3>
                <p class="empty-message">
                    You haven't purchased any tickets yet. Start by browsing our upcoming boxing events and get your tickets now!
                </p>
                <div class="empty-actions">
                    <a href="{{ route('events.index') }}" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        Browse Events
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Filter functionality
function applyFilters() {
    const statusFilter = document.getElementById('statusFilter').value;
    const eventFilter = document.getElementById('eventFilter').value.toLowerCase();
    const tickets = document.querySelectorAll('.ticket-card');

    tickets.forEach(ticket => {
        const ticketStatus = ticket.dataset.status;
        const ticketEvent = ticket.dataset.event;
        
        let showTicket = true;
        
        if (statusFilter && ticketStatus !== statusFilter) {
            showTicket = false;
        }
        
        if (eventFilter && !ticketEvent.includes(eventFilter)) {
            showTicket = false;
        }
        
        ticket.style.display = showTicket ? 'block' : 'none';
    });
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('eventFilter').value = '';
    
    const tickets = document.querySelectorAll('.ticket-card');
    tickets.forEach(ticket => {
        ticket.style.display = 'block';
    });
}

// Real-time search for event filter
document.getElementById('eventFilter').addEventListener('input', applyFilters);
document.getElementById('statusFilter').addEventListener('change', applyFilters);
</script>
@endsection