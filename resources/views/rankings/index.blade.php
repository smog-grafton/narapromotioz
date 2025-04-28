@extends('layouts.app')

@section('title', 'Boxing Rankings')

@section('content')
<div class="container py-5">
    <!-- Hero Banner -->
    <div class="position-relative mb-5">
        <div class="bg-dark rounded" style="height: 300px; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1622598453980-86acde940df4') center/cover no-repeat;">
        </div>
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
            <h1 class="display-4 fw-bold mb-3">BOXING RANKINGS</h1>
            <p class="lead">Official Nara Promotionz boxing rankings across all weight classes.</p>
        </div>
    </div>
    
    <!-- Weight Class Selection Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="rankings-tabs">
                <ul class="nav nav-pills justify-content-center" id="rankingsTabs" role="tablist">
                    @php
                        $weightClasses = [
                            'Heavyweight', 'Cruiserweight', 'Light Heavyweight', 'Super Middleweight', 
                            'Middleweight', 'Welterweight', 'Lightweight', 'Featherweight', 
                            'Bantamweight', 'Flyweight'
                        ];
                        $activeClass = $selectedClass ?? $weightClasses[0];
                    @endphp
                    
                    @foreach($weightClasses as $index => $weightClass)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $weightClass == $activeClass ? 'active' : '' }}" 
                                id="{{ Str::slug($weightClass) }}-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#{{ Str::slug($weightClass) }}" 
                                type="button" 
                                role="tab" 
                                aria-controls="{{ Str::slug($weightClass) }}" 
                                aria-selected="{{ $weightClass == $activeClass ? 'true' : 'false' }}">
                                {{ $weightClass }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Rankings Content -->
    <div class="tab-content" id="rankingsTabContent">
        @foreach($weightClasses as $weightClass)
            <div class="tab-pane fade {{ $weightClass == $activeClass ? 'show active' : '' }}" 
                id="{{ Str::slug($weightClass) }}" 
                role="tabpanel" 
                aria-labelledby="{{ Str::slug($weightClass) }}-tab">
                
                <!-- Champion Card -->
                @php
                    $champion = \App\Models\Fighter::where('weight_class', $weightClass)
                        ->where('is_champion', true)
                        ->first();
                @endphp
                
                @if($champion)
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card champion-card shadow">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        <div class="col-md-4 position-relative">
                                            <div class="champion-banner"></div>
                                            <div class="champion-image-container">
                                                @if($champion->profile_image)
                                                    <img src="{{ $champion->profile_image }}" class="champion-image" alt="{{ $champion->full_name }}">
                                                @else
                                                    <div class="champion-image-placeholder d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-user fa-4x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <div class="champion-badge mb-3">
                                                    <i class="fas fa-crown me-2"></i> CHAMPION
                                                </div>
                                                
                                                <h2 class="card-title mb-1">{{ $champion->full_name }}</h2>
                                                <p class="text-muted mb-3">{{ $champion->nickname ? "\"$champion->nickname\"" : "" }}</p>
                                                
                                                <div class="row mb-4">
                                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                                        <div class="record-stats">
                                                            <div class="record-stat">
                                                                <span class="record-value text-success">{{ $champion->wins }}</span>
                                                                <span class="record-label">Wins</span>
                                                            </div>
                                                            <div class="record-stat">
                                                                <span class="record-value text-danger">{{ $champion->losses }}</span>
                                                                <span class="record-label">Losses</span>
                                                            </div>
                                                            <div class="record-stat">
                                                                <span class="record-value text-primary">{{ $champion->draws }}</span>
                                                                <span class="record-label">Draws</span>
                                                            </div>
                                                            <div class="record-stat">
                                                                <span class="record-value text-warning">{{ $champion->knockouts }}</span>
                                                                <span class="record-label">KOs</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-sm-6">
                                                        <div class="fighter-details">
                                                            <div class="fighter-detail mb-2">
                                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $champion->country }}
                                                            </div>
                                                            <div class="fighter-detail mb-2">
                                                                <i class="fas fa-ruler-vertical me-2 text-primary"></i> {{ $champion->height ? $champion->height . ' cm' : 'N/A' }}
                                                            </div>
                                                            <div class="fighter-detail">
                                                                <i class="fas fa-calendar-alt me-2 text-primary"></i> {{ $champion->date_of_birth ? $champion->date_of_birth->format('F j, Y') : 'N/A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <a href="{{ route('fighters.show', $champion) }}" class="btn btn-primary">
                                                    <i class="fas fa-user me-2"></i> VIEW PROFILE
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Rankings Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">{{ $weightClass }} Rankings</h5>
                            </div>
                            <div class="card-body p-0">
                                @php
                                    $rankings = \App\Models\Ranking::where('weight_class', $weightClass)
                                        ->orderBy('position')
                                        ->with('fighter')
                                        ->get();
                                @endphp
                                
                                @if($rankings->isEmpty())
                                    <div class="p-4 text-center">
                                        <p class="text-muted mb-0">No rankings available for this weight class.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover rankings-table mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="80">Rank</th>
                                                    <th>Fighter</th>
                                                    <th>Record</th>
                                                    <th>Country</th>
                                                    <th>Points</th>
                                                    <th>Trend</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rankings as $ranking)
                                                    <tr>
                                                        <td class="text-center">
                                                            <span class="ranking-position">{{ $ranking->position }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($ranking->fighter->profile_image)
                                                                    <img src="{{ $ranking->fighter->profile_image }}" class="fighter-thumbnail me-3" alt="{{ $ranking->fighter->full_name }}">
                                                                @else
                                                                    <div class="fighter-thumbnail-placeholder me-3">
                                                                        <i class="fas fa-user"></i>
                                                                    </div>
                                                                @endif
                                                                
                                                                <div>
                                                                    <a href="{{ route('fighters.show', $ranking->fighter) }}" class="text-decoration-none fw-bold">
                                                                        {{ $ranking->fighter->full_name }}
                                                                    </a>
                                                                    @if($ranking->fighter->nickname)
                                                                        <div class="text-muted small">"{{ $ranking->fighter->nickname }}"</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-success">{{ $ranking->fighter->wins }}W</span> - 
                                                            <span class="text-danger">{{ $ranking->fighter->losses }}L</span> - 
                                                            <span class="text-primary">{{ $ranking->fighter->draws }}D</span>
                                                            <span class="text-muted">({{ $ranking->fighter->knockouts }} KOs)</span>
                                                        </td>
                                                        <td>{{ $ranking->fighter->country }}</td>
                                                        <td>{{ $ranking->points }}</td>
                                                        <td>
                                                            @if($ranking->previous_position > $ranking->position)
                                                                <span class="text-success d-flex align-items-center">
                                                                    <i class="fas fa-arrow-up me-1"></i> 
                                                                    <span>{{ $ranking->previous_position - $ranking->position }}</span>
                                                                </span>
                                                            @elseif($ranking->previous_position < $ranking->position)
                                                                <span class="text-danger d-flex align-items-center">
                                                                    <i class="fas fa-arrow-down me-1"></i> 
                                                                    <span>{{ $ranking->position - $ranking->previous_position }}</span>
                                                                </span>
                                                            @else
                                                                <span class="text-muted d-flex align-items-center">
                                                                    <i class="fas fa-minus me-1"></i>
                                                                    <span>0</span>
                                                                </span>
                                                            @endif
                                                        </td>
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
            </div>
        @endforeach
    </div>
    
    <!-- Rankings Information -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Rankings Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>How Rankings Are Determined</h6>
                            <p>
                                Our boxing rankings are based on a comprehensive point system that takes into account:
                            </p>
                            <ul>
                                <li>Win-loss record</li>
                                <li>Quality of opponents</li>
                                <li>Performance in recent fights</li>
                                <li>Activity level</li>
                                <li>Championship status</li>
                            </ul>
                            <p>
                                Rankings are updated after each fight card and reviewed by our panel of boxing experts 
                                to ensure fairness and accuracy.
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Weight Class Definitions</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Weight Class</th>
                                            <th>Weight Limit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Heavyweight</td>
                                            <td>Unlimited</td>
                                        </tr>
                                        <tr>
                                            <td>Cruiserweight</td>
                                            <td>200 lbs (90.7 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Light Heavyweight</td>
                                            <td>175 lbs (79.4 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Super Middleweight</td>
                                            <td>168 lbs (76.2 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Middleweight</td>
                                            <td>160 lbs (72.6 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Welterweight</td>
                                            <td>147 lbs (66.7 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Lightweight</td>
                                            <td>135 lbs (61.2 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Featherweight</td>
                                            <td>126 lbs (57.2 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Bantamweight</td>
                                            <td>118 lbs (53.5 kg)</td>
                                        </tr>
                                        <tr>
                                            <td>Flyweight</td>
                                            <td>112 lbs (50.8 kg)</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .rankings-tabs {
        overflow-x: auto;
        white-space: nowrap;
        padding: 10px 0;
    }
    
    .rankings-tabs .nav-item {
        margin: 0 5px;
    }
    
    .rankings-tabs .nav-link {
        border-radius: 30px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .rankings-tabs .nav-link.active {
        background-color: #e63946;
        color: white;
    }
    
    .champion-card {
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #eee;
    }
    
    .champion-image-container {
        position: relative;
        height: 100%;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .champion-banner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #1d3557, #457b9d);
        z-index: 0;
    }
    
    .champion-image {
        position: relative;
        max-width: 100%;
        max-height: 300px;
        z-index: 1;
    }
    
    .champion-image-placeholder {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: #f8f9fa;
        z-index: 1;
    }
    
    .champion-badge {
        display: inline-block;
        background-color: #ffc107;
        color: #212529;
        font-weight: bold;
        padding: 8px 20px;
        border-radius: 30px;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    
    .record-stats {
        display: flex;
        gap: 15px;
    }
    
    .record-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .record-value {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .record-label {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .fighter-thumbnail {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .fighter-thumbnail-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .ranking-position {
        display: inline-block;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 50%;
        font-weight: bold;
    }
    
    .rankings-table th {
        font-weight: 600;
    }
    
    .rankings-table tr:hover {
        background-color: rgba(0, 173, 239, 0.05);
    }
</style>
@endsection