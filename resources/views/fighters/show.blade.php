@extends('layouts.app')

@section('title', $fighter->full_name)

@section('content')
<div class="container py-5">
    <!-- Fighter Hero Section -->
    <div class="card shadow-sm mb-5 border-0">
        <div class="row g-0">
            <!-- Fighter Image Column -->
            <div class="col-md-4">
                <div class="h-100 fighter-image-bg d-flex align-items-center justify-content-center position-relative">
                    @if($fighter->profile_image)
                        <img src="{{ $fighter->profile_image }}" class="fighter-profile-image" alt="{{ $fighter->full_name }}">
                    @else
                        <div class="fighter-placeholder d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-4x text-muted"></i>
                        </div>
                    @endif
                    
                    @if($fighter->is_champion)
                        <div class="position-absolute top-0 start-0 p-3">
                            <div class="champion-badge">
                                <i class="fas fa-crown"></i> CHAMPION
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Fighter Info Column -->
            <div class="col-md-8">
                <div class="card-body py-4">
                    <h1 class="mb-1">{{ $fighter->full_name }}</h1>
                    @if($fighter->nickname)
                        <h5 class="text-muted mb-3">"{{ $fighter->nickname }}"</h5>
                    @endif
                    
                    <div class="d-flex flex-wrap mb-4">
                        <div class="me-4 mb-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <i class="fas fa-weight me-1"></i> {{ $fighter->weight_class }}
                            </span>
                        </div>
                        <div class="me-4 mb-2">
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="fas fa-flag me-1"></i> {{ $fighter->country }}
                            </span>
                        </div>
                        @if($fighter->ranking)
                            <div class="mb-2">
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    <i class="fas fa-trophy me-1"></i> Ranked #{{ $fighter->ranking->position }} {{ $fighter->weight_class }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Record Stats -->
                    <div class="row mb-4">
                        <div class="col-sm-3 col-6 mb-3 mb-sm-0">
                            <div class="text-center p-3 border rounded bg-light h-100">
                                <div class="h2 text-success mb-0">{{ $fighter->wins }}</div>
                                <div class="small text-muted">WINS</div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6 mb-3 mb-sm-0">
                            <div class="text-center p-3 border rounded bg-light h-100">
                                <div class="h2 text-danger mb-0">{{ $fighter->losses }}</div>
                                <div class="small text-muted">LOSSES</div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="text-center p-3 border rounded bg-light h-100">
                                <div class="h2 text-primary mb-0">{{ $fighter->draws }}</div>
                                <div class="small text-muted">DRAWS</div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="text-center p-3 border rounded bg-light h-100">
                                <div class="h2 text-warning mb-0">{{ $fighter->knockouts }}</div>
                                <div class="small text-muted">KOs</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6>BASIC INFO</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted">Age:</td>
                                    <td>{{ $fighter->date_of_birth ? now()->diff($fighter->date_of_birth)->y . ' years' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Height:</td>
                                    <td>{{ $fighter->height ? $fighter->height . ' cm' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Weight:</td>
                                    <td>{{ $fighter->weight ? $fighter->weight . ' kg' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Reach:</td>
                                    <td>{{ $fighter->reach ? $fighter->reach . ' cm' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Stance:</td>
                                    <td>{{ $fighter->stance ?: 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>FIGHT CAREER</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted">Debut:</td>
                                    <td>{{ $fighter->pro_debut_date ? $fighter->pro_debut_date->format('F j, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pro Years:</td>
                                    <td>{{ $fighter->pro_debut_date ? now()->diff($fighter->pro_debut_date)->y : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Fights:</td>
                                    <td>{{ $fighter->wins + $fighter->losses + $fighter->draws }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">KO Percentage:</td>
                                    <td>{{ $fighter->wins > 0 ? round(($fighter->knockouts / $fighter->wins) * 100) . '%' : '0%' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Win Percentage:</td>
                                    <td>{{ ($fighter->wins + $fighter->losses + $fighter->draws) > 0 ? round(($fighter->wins / ($fighter->wins + $fighter->losses + $fighter->draws)) * 100) . '%' : '0%' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fighter Biography -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Biography</h5>
                </div>
                <div class="card-body">
                    @if($fighter->biography)
                        <div class="fighter-biography">
                            {!! $fighter->biography !!}
                        </div>
                    @else
                        <p class="text-muted mb-0">No biography available for this fighter.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fighter Fight History -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Fight History</h5>
                </div>
                <div class="card-body p-0">
                    @if($fighter->fights->isEmpty())
                        <div class="p-4 text-center">
                            <p class="text-muted mb-0">No fight history available for this fighter.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Event</th>
                                        <th>Opponent</th>
                                        <th class="text-center">Result</th>
                                        <th>Method</th>
                                        <th class="text-center">Round</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fighter->fights as $fight)
                                        <tr>
                                            <td>{{ $fight->event->event_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('events.show', $fight->event) }}" class="text-decoration-none">
                                                    {{ $fight->event->title }}
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $opponent = $fight->fighterOne->id === $fighter->id 
                                                        ? $fight->fighterTwo 
                                                        : $fight->fighterOne;
                                                @endphp
                                                <a href="{{ route('fighters.show', $opponent) }}" class="text-decoration-none">
                                                    {{ $opponent->full_name }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                @if($fight->winner_id)
                                                    @if($fight->winner_id === $fighter->id)
                                                        <span class="badge bg-success">WIN</span>
                                                    @else
                                                        <span class="badge bg-danger">LOSS</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-primary">DRAW</span>
                                                @endif
                                            </td>
                                            <td>{{ $fight->result_method ?: 'N/A' }}</td>
                                            <td class="text-center">{{ $fight->result_round ?: 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Fights -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Upcoming Fights</h5>
                </div>
                <div class="card-body">
                    @php
                        $upcomingFights = $fighter->fights()
                            ->whereHas('event', function($query) {
                                $query->where('event_date', '>', now());
                            })
                            ->with(['event', 'fighterOne', 'fighterTwo'])
                            ->get();
                    @endphp
                    
                    @if($upcomingFights->isEmpty())
                        <div class="text-center">
                            <p class="text-muted mb-0">No upcoming fights scheduled for this fighter.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($upcomingFights as $fight)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $fight->event->title }}</h5>
                                            <p class="card-text">
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                {{ $fight->event->event_date->format('F j, Y') }}
                                            </p>
                                            <p class="card-text">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                {{ $fight->event->location }}
                                            </p>
                                            
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div class="text-center">
                                                    <img src="{{ $fight->fighterOne->profile_image }}" class="rounded-circle" width="60" height="60" 
                                                        style="object-fit: cover;" alt="{{ $fight->fighterOne->full_name }}">
                                                    <p class="mb-0 mt-2">{{ $fight->fighterOne->full_name }}</p>
                                                    <small class="text-muted">{{ $fight->fighterOne->wins }}-{{ $fight->fighterOne->losses }}</small>
                                                </div>
                                                
                                                <div>
                                                    <span class="badge bg-danger px-3 py-2">VS</span>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <img src="{{ $fight->fighterTwo->profile_image }}" class="rounded-circle" width="60" height="60"
                                                        style="object-fit: cover;" alt="{{ $fight->fighterTwo->full_name }}">
                                                    <p class="mb-0 mt-2">{{ $fight->fighterTwo->full_name }}</p>
                                                    <small class="text-muted">{{ $fight->fighterTwo->wins }}-{{ $fight->fighterTwo->losses }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <a href="{{ route('events.show', $fight->event) }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-ticket-alt me-2"></i> VIEW EVENT
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Fighters -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Similar Fighters</h5>
                </div>
                <div class="card-body">
                    @php
                        $similarFighters = \App\Models\Fighter::where('weight_class', $fighter->weight_class)
                            ->where('id', '!=', $fighter->id)
                            ->take(4)
                            ->get();
                    @endphp
                    
                    @if($similarFighters->isEmpty())
                        <div class="text-center">
                            <p class="text-muted mb-0">No similar fighters found.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($similarFighters as $similarFighter)
                                <div class="col-md-3 col-6">
                                    <div class="text-center mb-3">
                                        <a href="{{ route('fighters.show', $similarFighter) }}" class="text-decoration-none">
                                            @if($similarFighter->profile_image)
                                                <img src="{{ $similarFighter->profile_image }}" class="rounded-circle mb-2" width="80" height="80"
                                                    style="object-fit: cover;" alt="{{ $similarFighter->full_name }}">
                                            @else
                                                <div class="rounded-circle border d-flex align-items-center justify-content-center mx-auto mb-2" 
                                                    style="width: 80px; height: 80px; background-color: #f8f9fa;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <h6 class="mb-0">{{ $similarFighter->full_name }}</h6>
                                            <p class="text-muted small mb-0">{{ $similarFighter->wins }}-{{ $similarFighter->losses }}-{{ $similarFighter->draws }}</p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .fighter-image-bg {
        background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8));
        min-height: 400px;
    }
    
    .fighter-profile-image {
        max-height: 400px;
        max-width: 100%;
        object-fit: contain;
    }
    
    .fighter-placeholder {
        width: 100%;
        height: 100%;
    }
    
    .champion-badge {
        background-color: #ffc107;
        color: #212529;
        font-weight: bold;
        padding: 5px 15px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .fighter-biography {
        line-height: 1.8;
    }
    
    .fighter-biography p {
        margin-bottom: 1rem;
    }
</style>
@endsection