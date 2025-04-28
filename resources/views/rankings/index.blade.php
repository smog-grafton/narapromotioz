@extends('layouts.app')

@section('title', 'Rankings')

@section('content')
<div class="container py-5">
    <!-- Hero Banner -->
    <div class="position-relative mb-5">
        <div class="bg-dark rounded" style="height: 300px; background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.8)), url('https://images.unsplash.com/photo-1613334171594-38094e8b870e') center/cover no-repeat;">
        </div>
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
            <h1 class="display-4 fw-bold mb-3">OFFICIAL RANKINGS</h1>
            <p class="lead">The definitive ranking of the world's best boxers by weight class.</p>
        </div>
    </div>
    
    <!-- Weight Class Selection -->
    <div class="row mb-5">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('rankings.index') }}" method="GET" class="d-flex flex-column flex-md-row align-items-center">
                        <div class="form-group flex-grow-1 mb-3 mb-md-0 me-md-2">
                            <label for="weight_class" class="form-label fw-bold">Select Weight Class</label>
                            <select name="weight_class" id="weight_class" class="form-select" onchange="this.form.submit()">
                                @foreach($weightClasses as $class)
                                    <option value="{{ $class }}" {{ $selectedWeightClass == $class ? 'selected' : '' }}>
                                        {{ $class }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rankings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h3>{{ $selectedWeightClass }} Division Rankings</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover rankings-table mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rank</th>
                                    <th>Fighter</th>
                                    <th class="text-center">Record</th>
                                    <th class="text-center">Points</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rankings as $ranking)
                                    <tr class="{{ $ranking->isChampion() ? 'champion' : '' }}">
                                        <td class="align-middle" width="80">
                                            <span class="badge {{ $ranking->isChampion() ? 'bg-warning text-dark' : 'bg-secondary' }} p-2">
                                                {{ $ranking->position }}
                                                @if($ranking->isChampion())
                                                    <i class="fas fa-crown ms-1"></i>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                @if($ranking->fighter->profile_image)
                                                    <img src="{{ $ranking->fighter->profile_image }}" class="rounded-circle me-3" width="50" height="50" alt="{{ $ranking->fighter->full_name }}">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle me-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('fighters.show', $ranking->fighter) }}" class="text-decoration-none">
                                                        <strong>{{ $ranking->fighter->full_name }}</strong>
                                                    </a>
                                                    @if($ranking->fighter->nationality)
                                                        <br>
                                                        <small class="text-muted">{{ $ranking->fighter->nationality }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="text-success">{{ $ranking->fighter->wins }}</span>-
                                            <span class="text-danger">{{ $ranking->fighter->losses }}</span>-
                                            <span class="text-secondary">{{ $ranking->fighter->draws }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ number_format($ranking->points, 2) }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $ranking->last_updated->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">
                                            No rankings available for this weight class.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ranking Explanation -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Rankings Methodology</h4>
                </div>
                <div class="card-body">
                    <p>Our rankings are updated after each significant bout and are based on a carefully calculated points system that takes into account:</p>
                    <ul>
                        <li>Win/loss record and quality of opponents</li>
                        <li>Championship status and title defenses</li>
                        <li>Performance in recent fights</li>
                        <li>Level of activity</li>
                        <li>Knockout percentage</li>
                    </ul>
                    <p>These rankings are reviewed and updated by our panel of boxing experts to ensure they accurately reflect each fighter's standing in their division.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="row mt-5">
        <div class="col-12 bg-dark text-white p-4 rounded">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">SEE THE CHAMPIONS IN ACTION</h4>
                    <p class="mb-md-0">Don't miss our upcoming championship bouts and elite boxing matchups.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('events.index') }}" class="btn btn-danger">
                        <i class="fas fa-calendar-alt me-2"></i> VIEW EVENTS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection