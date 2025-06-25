@php
    // Fetch upcoming events
    $upcomingEvents = App\Models\BoxingEvent::where('event_date', '>', \Carbon\Carbon::now())
        ->where('status', 'upcoming')
        ->orderBy('event_date', 'asc')
        ->take(6)
        ->get();
        
    // Fetch past events
    $pastEvents = App\Models\BoxingEvent::where(function($query) {
            $query->where('event_date', '<', \Carbon\Carbon::now())
                ->orWhere('status', 'completed');
        })
        ->orderBy('event_date', 'desc')
        ->take(6)
        ->get();
@endphp

<!-- Events Section -->
<section class="events-section">
    <div class="bg-text">EVENTS</div>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="section-title">EVENTS</h2>
                <div class="event-tabs mt-3">
                    <a href="#upcoming" class="event-tab active" data-tab="upcoming">UPCOMING</a>
                    <a href="#past" class="event-tab" data-tab="past">PAST</a>
                </div>
            </div>
        </div>

        <!-- Upcoming Events (Default Active) -->
        <div id="upcoming" class="event-tab-content active">
            @if($upcomingEvents->count() > 0)
                <!-- Desktop View (md and up) -->
                <div class="desktop-events d-none d-md-block">
                    @foreach($upcomingEvents as $event)
                        <div class="row event-item-row">
                            <div class="col-md-4 event-image-container">
                                <img src="{{ $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/events/default-poster.jpg') }}" 
                                     alt="{{ $event->name }}" class="event-image">
                            </div>
                            <div class="col-md-8 event-details-container d-flex flex-column">
                                <div class="event-info">
                                    <div class="event-date">{{ strtoupper(\Carbon\Carbon::parse($event->event_date)->format('F j, Y')) }}</div>
                                    <h3 class="event-name">{{ strtoupper($event->name) }}</h3>
                                    <div class="event-venue">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $event->venue }}, {{ $event->city }}, {{ $event->country }}
                                    </div>
                                    <div class="event-network">
                                        <i class="fas fa-tv"></i>
                                        {{ $event->broadcast_network ? 'Live on ' . $event->broadcast_network : ($event->network ? 'Live on ' . $event->network : 'Live Event') }}
                                    </div>
                                </div>
                                <div class="event-actions mt-3">
                                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-primary">
                                        @if($event->tickets_available)
                                            <i class="fas fa-ticket-alt"></i>
                                            GET TICKETS
                                        @else
                                            <i class="fas fa-info-circle"></i>
                                            EVENT DETAILS
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile View (sm and down) -->
                <div class="mobile-events d-block d-md-none">
                    @foreach($upcomingEvents as $event)
                        <div class="event-card">
                            <div class="event-card-image">
                                <img src="{{ $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/events/default-poster.jpg') }}" 
                                     alt="{{ $event->name }}" class="card-img-top">
                                <div class="event-card-overlay">
                                    <div class="event-card-content">
                                        <h3 class="event-name">{{ strtoupper($event->name) }}</h3>
                                        <div class="event-date">{{ strtoupper(\Carbon\Carbon::parse($event->event_date)->format('F j, Y')) }}</div>
                                        <div class="event-venue">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $event->venue }}, {{ $event->city }}
                                        </div>
                                        <div class="event-network">
                                            <i class="fas fa-tv"></i>
                                            {{ $event->broadcast_network ? 'Live on ' . $event->broadcast_network : ($event->network ? 'Live on ' . $event->network : 'Live Event') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-primary">
                                    @if($event->tickets_available)
                                        <i class="fas fa-ticket-alt"></i>
                                        GET TICKETS
                                    @else
                                        <i class="fas fa-info-circle"></i>
                                        EVENT DETAILS
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    No upcoming events scheduled at this time. Check back soon for updates.
                </div>
            @endif
        </div>

        <!-- Past Events (Initially Hidden) -->
        <div id="past" class="event-tab-content">
            @if($pastEvents->count() > 0)
                <!-- Desktop View (md and up) -->
                <div class="desktop-events d-none d-md-block">
                    @foreach($pastEvents as $event)
                        <div class="row event-item-row">
                            <div class="col-md-4 event-image-container">
                                <img src="{{ $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/events/default-poster.jpg') }}" 
                                     alt="{{ $event->name }}" class="event-image">
                            </div>
                            <div class="col-md-8 event-details-container d-flex flex-column">
                                <div class="event-info">
                                    <div class="event-date">{{ strtoupper(\Carbon\Carbon::parse($event->event_date)->format('F j, Y')) }}</div>
                                    <h3 class="event-name">{{ strtoupper($event->name) }}</h3>
                                    <div class="event-venue">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $event->venue }}, {{ $event->city }}, {{ $event->country }}
                                    </div>
                                    <div class="event-network">
                                        <i class="fas fa-tv"></i>
                                        {{ $event->broadcast_network ? 'Aired on ' . $event->broadcast_network : ($event->network ? 'Aired on ' . $event->network : 'Past Event') }}
                                    </div>
                                </div>
                                <div class="event-actions mt-3">
                                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-secondary">
                                        <i class="fas fa-play-circle"></i>
                                        WATCH HIGHLIGHTS
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile View (sm and down) -->
                <div class="mobile-events d-block d-md-none">
                    @foreach($pastEvents as $event)
                        <div class="event-card">
                            <div class="event-card-image">
                                <img src="{{ $event->image_path ? asset('storage/' . $event->image_path) : asset('assets/images/events/default-poster.jpg') }}" 
                                     alt="{{ $event->name }}" class="card-img-top">
                                <div class="event-card-overlay">
                                    <div class="event-card-content">
                                        <h3 class="event-name">{{ strtoupper($event->name) }}</h3>
                                        <div class="event-date">{{ strtoupper(\Carbon\Carbon::parse($event->event_date)->format('F j, Y')) }}</div>
                                        <div class="event-venue">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $event->venue }}, {{ $event->city }}
                                        </div>
                                        <div class="event-network">
                                            <i class="fas fa-tv"></i>
                                            {{ $event->broadcast_network ? 'Aired on ' . $event->broadcast_network : ($event->network ? 'Aired on ' . $event->network : 'Past Event') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-secondary">
                                    <i class="fas fa-play-circle"></i>
                                    WATCH HIGHLIGHTS
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    No past events available at this time.
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Tab Switching Script -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all tab elements
        const tabs = document.querySelectorAll('.event-tab');
        
        // Add click event listeners to each tab
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the tab id from data attribute
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and content
                document.querySelectorAll('.event-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.event-tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    });
</script>
@endpush 