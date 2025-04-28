@extends('layouts.app')

@section('title', 'Buy Tickets - ' . $event->title)

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="border-start border-primary border-5 ps-3">BUY TICKETS</h1>
            <p class="text-muted">Purchase your tickets for {{ $event->title }}.</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Event Info -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card shadow-sm h-100">
                <div class="position-relative">
                    @if($event->event_banner)
                        <img src="{{ $event->event_banner }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $event->title }}">
                    @else
                        <div class="bg-dark text-white d-flex justify-content-center align-items-center" style="height: 200px;">
                            <h5>{{ $event->title }}</h5>
                        </div>
                    @endif
                    
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary">{{ $event->event_date->format('M d, Y') }}</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <p class="card-text">
                        <i class="fas fa-calendar-alt me-2"></i> {{ $event->event_date->format('F j, Y - g:i A') }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ $event->location }}
                    </p>
                    
                    <!-- Main Event Fighters (if any) -->
                    @if($event->fights->isNotEmpty())
                        <?php $mainEvent = $event->fights->sortBy('fight_order')->first(); ?>
                        <div class="card mt-3">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">MAIN EVENT</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">{{ $mainEvent->fighterOne->full_name }}</p>
                                        <small>{{ $mainEvent->fighterOne->wins }}-{{ $mainEvent->fighterOne->losses }}</small>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-danger">VS</span>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-0 fw-bold">{{ $mainEvent->fighterTwo->full_name }}</p>
                                        <small>{{ $mainEvent->fighterTwo->wins }}-{{ $mainEvent->fighterTwo->losses }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Ticket Purchase Form -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Ticket Purchase</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Ticket Price -->
                    <div class="alert alert-primary d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle fa-lg me-3"></i>
                        <div>
                            <strong>Ticket Price:</strong> ${{ number_format($event->ticket_price, 2) }} per ticket
                        </div>
                    </div>
                    
                    <form action="{{ route('tickets.store', $event) }}" method="POST">
                        @csrf
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Payment Method</label>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card payment-method-card h-100">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="pesapal" value="pesapal" checked>
                                                <label class="form-check-label w-100" for="pesapal">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 bg-light rounded p-2">
                                                            <i class="fas fa-credit-card fa-lg text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Pesapal</h6>
                                                            <small class="text-muted">Credit/Debit Card</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card payment-method-card h-100">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="airtel_money" value="airtel_money">
                                                <label class="form-check-label w-100" for="airtel_money">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 bg-light rounded p-2">
                                                            <i class="fas fa-mobile-alt fa-lg text-danger"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Airtel Money</h6>
                                                            <small class="text-muted">Mobile Payment</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card payment-method-card h-100">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="mtn_money" value="mtn_money">
                                                <label class="form-check-label w-100" for="mtn_money">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 bg-light rounded p-2">
                                                            <i class="fas fa-money-bill-wave fa-lg text-warning"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">MTN Money</h6>
                                                            <small class="text-muted">Mobile Payment</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @error('payment_method')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> and <a href="#" class="text-decoration-none">Refund Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ticket Price:</span>
                                    <span>${{ number_format($event->ticket_price, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Processing Fee:</span>
                                    <span>$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>${{ number_format($event->ticket_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i> PROCEED TO PAYMENT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add styling to payment method cards
        const paymentCards = document.querySelectorAll('.payment-method-card');
        const radioInputs = document.querySelectorAll('input[name="payment_method"]');
        
        function updateCardStyles() {
            radioInputs.forEach(input => {
                const card = input.closest('.payment-method-card');
                if (input.checked) {
                    card.classList.add('border-primary');
                    card.classList.add('shadow-sm');
                } else {
                    card.classList.remove('border-primary');
                    card.classList.remove('shadow-sm');
                }
            });
        }
        
        // Initial style
        updateCardStyles();
        
        // Add event listeners to all radio inputs
        radioInputs.forEach(input => {
            input.addEventListener('change', updateCardStyles);
        });
        
        // Add click event to the cards
        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Trigger the change event manually
                const event = new Event('change');
                radio.dispatchEvent(event);
            });
        });
    });
</script>
@endsection