@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Dashboard" 
    subtitle="Welcome back! Here's your boxing events overview"
    :breadcrumbs="[['title' => 'Dashboard']]" />

<div class="dashboard-container">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-info">
                <h1 class="dashboard-title">Welcome Back, {{ Auth::user()->name }}!</h1>
                <p class="dashboard-subtitle">Manage your tickets and explore upcoming events</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('events.index') }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                    Browse Events
                </a>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon red">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Total Tickets</div>
                        <div class="stat-value">{{ $ticketPurchases->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon yellow">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Upcoming Events</div>
                        <div class="stat-value">{{ $upcomingEvents->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon green">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Active</div>
                        <div class="stat-value">{{ $ticketPurchases->where('status', 'confirmed')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content Grid -->
        <div class="dashboard-grid">
            <!-- Recent Tickets -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Ticket Purchases</h3>
                    <a href="{{ route('tickets.my-tickets') }}" class="card-action">View All</a>
                </div>
                <div class="card-content">
                    @if($ticketPurchases->count() > 0)
                        <div class="recent-items">
                            @foreach($ticketPurchases as $purchase)
                                <div class="recent-item">
                                    <div class="item-content">
                                        <div class="item-title">{{ $purchase->boxingEvent->title ?? 'Event' }}</div>
                                        <div class="item-meta">
                                            {{ $purchase->created_at->format('M j, Y') }} • {{ $purchase->quantity }} ticket(s)
                                            <div class="item-ref">Ref: {{ $purchase->reference_number }}</div>
                                        </div>
                                    </div>
                                    <div class="item-status">
                                        <span class="status-badge {{ strtolower($purchase->status) }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <svg class="empty-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20,8H4V6H20M20,18H4V12H20V18Z"/>
                            </svg>
                            <h4 class="empty-title">No Tickets Yet</h4>
                            <p class="empty-message">You haven't purchased any tickets yet. Browse our upcoming events to get started!</p>
                            <div class="empty-actions">
                                <a href="{{ route('events.index') }}" class="btn btn-primary">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                    </svg>
                                    Browse Events
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Upcoming Events</h3>
                    <a href="{{ route('events.index') }}" class="card-action">View All</a>
                </div>
                <div class="card-content">
                    @if($upcomingEvents->count() > 0)
                        <div class="upcoming-events">
                            @foreach($upcomingEvents as $event)
                                <div class="event-item">
                                    @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="event-image">
                                    @else
                                        <div class="event-image placeholder">
                                            <svg viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="event-content">
                                        <div class="event-title">{{ $event->title }}</div>
                                        <div class="event-meta">{{ $event->event_date->format('M j, Y') }} • {{ $event->location }}</div>
                                    </div>
                                    <div class="event-action">
                                        <a href="{{ route('events.show', $event) }}" class="btn-link">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <svg class="empty-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                            </svg>
                            <h4 class="empty-title">No Upcoming Events</h4>
                            <p class="empty-message">There are no upcoming events scheduled at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Latest News Section -->
        @if($latestNews->count() > 0)
        <div class="news-section">
            <div class="news-section-card">
                <div class="news-header">
                    <h3 class="news-title">Latest Boxing News</h3>
                </div>
                <div class="news-content">
                    <div class="news-grid">
                        @foreach($latestNews as $news)
                            <div class="news-article">
                                @if($news->featured_image)
                                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="article-image">
                                @endif
                                <div class="article-content">
                                    <h4 class="article-title">{{ $news->title }}</h4>
                                    <p class="article-excerpt">{{ Str::limit(strip_tags($news->content), 120) }}</p>
                                    <div class="article-footer">
                                        <span class="article-date">{{ $news->created_at->format('M j, Y') }}</span>
                                        <a href="{{ route('news.show', $news) }}" class="article-link">Read More</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 