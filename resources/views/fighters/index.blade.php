@extends('layouts.app')

@section('title', 'Boxers & Fighters')

@section('content')
<div class="container py-5">
    <!-- Hero Banner -->
    <div class="position-relative mb-5">
        <div class="bg-dark rounded" style="height: 300px; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1549719386-74dfcbf7dbed') center/cover no-repeat;">
        </div>
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
            <h1 class="display-4 fw-bold mb-3">BOXERS & FIGHTERS</h1>
            <p class="lead">Explore our roster of world-class boxing talent.</p>
        </div>
    </div>
    
    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3 mb-md-0">
            <form action="{{ route('fighters.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search fighters..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="d-flex">
                <select name="weight_class" class="form-select me-2" onchange="this.form.submit()">
                    <option value="">All Weight Classes</option>
                    <option value="Heavyweight" {{ request('weight_class') == 'Heavyweight' ? 'selected' : '' }}>Heavyweight</option>
                    <option value="Cruiserweight" {{ request('weight_class') == 'Cruiserweight' ? 'selected' : '' }}>Cruiserweight</option>
                    <option value="Light Heavyweight" {{ request('weight_class') == 'Light Heavyweight' ? 'selected' : '' }}>Light Heavyweight</option>
                    <option value="Super Middleweight" {{ request('weight_class') == 'Super Middleweight' ? 'selected' : '' }}>Super Middleweight</option>
                    <option value="Middleweight" {{ request('weight_class') == 'Middleweight' ? 'selected' : '' }}>Middleweight</option>
                    <option value="Welterweight" {{ request('weight_class') == 'Welterweight' ? 'selected' : '' }}>Welterweight</option>
                    <option value="Lightweight" {{ request('weight_class') == 'Lightweight' ? 'selected' : '' }}>Lightweight</option>
                    <option value="Featherweight" {{ request('weight_class') == 'Featherweight' ? 'selected' : '' }}>Featherweight</option>
                    <option value="Bantamweight" {{ request('weight_class') == 'Bantamweight' ? 'selected' : '' }}>Bantamweight</option>
                    <option value="Flyweight" {{ request('weight_class') == 'Flyweight' ? 'selected' : '' }}>Flyweight</option>
                </select>
                
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="wins" {{ request('sort') == 'wins' ? 'selected' : '' }}>Most Wins</option>
                    <option value="rank" {{ request('sort') == 'rank' ? 'selected' : '' }}>Ranking</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Fighters Grid -->
    <div class="row">
        @forelse($fighters as $fighter)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm fighter-card position-relative">
                    @if($fighter->is_champion)
                        <div class="position-absolute top-0 start-50 translate-middle">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow">
                                <i class="fas fa-crown me-1"></i> CHAMPION
                            </span>
                        </div>
                    @endif
                    
                    <div class="fighter-image-container">
                        @if($fighter->profile_image)
                            <img src="{{ $fighter->profile_image }}" class="fighter-image" alt="{{ $fighter->full_name }}">
                        @else
                            <div class="fighter-image-placeholder d-flex justify-content-center align-items-center bg-light text-muted">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body text-center">
                        <h5 class="card-title mb-1">{{ $fighter->full_name }}</h5>
                        <p class="text-muted small mb-2">{{ $fighter->nickname ? "\"$fighter->nickname\"" : "" }}</p>
                        
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <div class="text-center">
                                <span class="text-success fw-bold d-block">{{ $fighter->wins }}</span>
                                <small class="text-muted">WINS</small>
                            </div>
                            <div class="text-center">
                                <span class="text-danger fw-bold d-block">{{ $fighter->losses }}</span>
                                <small class="text-muted">LOSSES</small>
                            </div>
                            <div class="text-center">
                                <span class="text-primary fw-bold d-block">{{ $fighter->draws }}</span>
                                <small class="text-muted">DRAWS</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 small text-muted">
                            <span>
                                <i class="fas fa-weight me-1"></i> {{ $fighter->weight_class }}
                            </span>
                            <span>
                                <i class="fas fa-flag me-1"></i> {{ $fighter->country }}
                            </span>
                        </div>
                        
                        <a href="{{ route('fighters.show', $fighter) }}" class="btn btn-outline-primary btn-sm w-100">
                            VIEW PROFILE
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No fighters found matching your criteria.
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $fighters->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .fighter-card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .fighter-card:hover {
        transform: translateY(-5px);
    }
    
    .fighter-image-container {
        height: 250px;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .fighter-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top;
    }
    
    .fighter-image-placeholder {
        height: 100%;
    }
</style>
@endsection