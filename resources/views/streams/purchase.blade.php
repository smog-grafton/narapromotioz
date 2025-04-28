@extends('layouts.app')

@section('title', 'Purchase Stream Access')

@section('styles')
<style>
    .purchase-header {
        background-color: var(--dark-navy);
        padding: 3rem 0;
        color: var(--white);
    }
    
    .purchase-content {
        padding: 3rem 0;
    }
    
    .purchase-summary {
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .purchase-summary-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .purchase-summary-image {
        width: 120px;
        height: 80px;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        margin-right: 1.5rem;
        flex-shrink: 0;
    }
    
    .purchase-summary-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .purchase-summary-details {
        flex: 1;
    }
    
    .purchase-summary-title {
        font-size: 1.5rem;
        font-weight: var(--font-bold);
        margin-bottom: 0.5rem;
    }
    
    .purchase-summary-meta {
        color: #666;
        font-size: 0.9rem;
    }
    
    .purchase-summary-list {
        margin-bottom: 1.5rem;
    }
    
    .purchase-summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    
    .purchase-summary-item.total {
        font-weight: var(--font-bold);
        border-top: 1px solid var(--light-gray);
        padding-top: 0.75rem;
        font-size: 1.1rem;
    }
    
    .payment-methods {
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
    }
    
    .payment-method-list {
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius-sm);
        margin-top: 1.5rem;
    }
    
    .payment-method-item {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        border-bottom: 1px solid var(--light-gray);
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .payment-method-item:last-child {
        border-bottom: none;
    }
    
    .payment-method-item:hover {
        background-color: rgba(0, 0, 0, 0.025);
    }
    
    .payment-method-item.selected {
        background-color: rgba(0, 173, 239, 0.05);
    }
    
    .payment-method-radio {
        margin-right: 1rem;
    }
    
    .payment-method-logo {
        width: 60px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .payment-method-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .payment-method-info {
        flex: 1;
    }
    
    .payment-method-name {
        font-weight: var(--font-medium);
        margin-bottom: 0.25rem;
    }
    
    .payment-method-description {
        font-size: 0.9rem;
        color: #666;
    }
    
    .mobile-payment-section {
        display: none;
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .mobile-payment-section.active {
        display: block;
    }
    
    .mobile-payment-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .mobile-payment-logo {
        width: 60px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .mobile-payment-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .mobile-payment-form-group {
        margin-bottom: 1.5rem;
    }
    
    .mobile-payment-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: var(--font-medium);
    }
    
    .mobile-payment-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        border-radius: var(--border-radius-sm);
        transition: border-color 0.3s ease;
        font-size: 1rem;
    }
    
    .mobile-payment-input:focus {
        border-color: var(--sky-blue);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 173, 239, 0.25);
    }
    
    .mobile-payment-help {
        margin-top: 0.25rem;
        font-size: 0.85rem;
        color: #666;
    }
    
    .payment-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
    
    .payment-info {
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
    }
    
    .payment-info-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .payment-info-title {
        font-size: 1.2rem;
        font-weight: var(--font-bold);
        margin-bottom: 0.5rem;
    }
    
    .payment-info-item {
        margin-bottom: 1.25rem;
    }
    
    .payment-info-label {
        font-weight: var(--font-medium);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .payment-feature-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    
    .payment-feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .payment-feature-item:last-child {
        margin-bottom: 0;
    }
    
    .payment-feature-item i {
        color: var(--sky-blue);
        margin-right: 0.75rem;
    }
    
    .secure-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2rem;
        color: #666;
        font-size: 0.9rem;
    }
    
    .secure-badge i {
        margin-right: 0.5rem;
        color: var(--sky-blue);
    }
    
    @media (max-width: 991.98px) {
        .payment-info {
            margin-top: 2rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Purchase Header -->
<section class="purchase-header">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <h1 class="mb-3">Purchase Stream Access</h1>
                <p class="lead mb-0">Choose your preferred payment method to access this boxing event.</p>
            </div>
        </div>
    </div>
</section>

<!-- Purchase Content -->
<section class="purchase-content">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Purchase Summary -->
                <div class="purchase-summary">
                    <div class="purchase-summary-header">
                        <div class="purchase-summary-image">
                            <img src="{{ isset($stream) && $stream->thumbnail_url ? $stream->thumbnail_url : asset('images/event-thumbnail.jpg') }}" alt="{{ isset($stream) ? $stream->title : 'Boxing Event' }}">
                        </div>
                        <div class="purchase-summary-details">
                            <h2 class="purchase-summary-title">{{ isset($stream) ? $stream->title : 'Championship Boxing Match' }}</h2>
                            <div class="purchase-summary-meta">
                                <div><i class="far fa-calendar-alt me-2"></i> {{ isset($stream) && isset($stream->scheduled_start) ? $stream->scheduled_start->format('F j, Y - g:i A') : 'December 25, 2023 - 8:00 PM' }}</div>
                                @if(isset($stream) && $stream->status == 'live')
                                    <div class="mt-1"><span class="badge bg-danger">LIVE NOW</span></div>
                                @elseif(isset($stream) && $stream->status == 'upcoming')
                                    <div class="mt-1"><span class="badge bg-primary">UPCOMING</span></div>
                                @elseif(isset($stream) && $stream->status == 'ended')
                                    <div class="mt-1"><span class="badge bg-secondary">REPLAY AVAILABLE</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="purchase-summary-list">
                        <div class="purchase-summary-item">
                            <div>Stream Access</div>
                            <div>${{ isset($stream) && isset($stream->price) ? number_format($stream->price, 2) : '19.99' }}</div>
                        </div>
                        <div class="purchase-summary-item">
                            <div>Platform Fee</div>
                            <div>${{ isset($stream) && isset($stream->price) ? number_format($stream->price * 0.05, 2) : '1.00' }}</div>
                        </div>
                        <div class="purchase-summary-item total">
                            <div>Total</div>
                            <div>${{ isset($stream) && isset($stream->price) ? number_format($stream->price * 1.05, 2) : '20.99' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h3>Select Payment Method</h3>
                    <div class="payment-method-list">
                        <!-- Credit Card -->
                        <div class="payment-method-item" data-method="credit_card">
                            <input type="radio" name="payment_method" id="credit_card" class="payment-method-radio" checked>
                            <div class="payment-method-logo">
                                <img src="{{ asset('images/credit-cards.png') }}" alt="Credit Cards">
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Credit/Debit Card</div>
                                <div class="payment-method-description">Pay with Visa, Mastercard, or American Express</div>
                            </div>
                        </div>
                        
                        <!-- PayPal -->
                        <div class="payment-method-item" data-method="paypal">
                            <input type="radio" name="payment_method" id="paypal" class="payment-method-radio">
                            <div class="payment-method-logo">
                                <img src="{{ asset('images/paypal.png') }}" alt="PayPal">
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">PayPal</div>
                                <div class="payment-method-description">Pay using your PayPal account</div>
                            </div>
                        </div>
                        
                        <!-- Airtel Money -->
                        <div class="payment-method-item" data-method="airtel">
                            <input type="radio" name="payment_method" id="airtel" class="payment-method-radio">
                            <div class="payment-method-logo">
                                <img src="{{ asset('images/airtel-money.png') }}" alt="Airtel Money">
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Airtel Money</div>
                                <div class="payment-method-description">Pay using your Airtel Money mobile wallet</div>
                            </div>
                        </div>
                        
                        <!-- MTN Mobile Money -->
                        <div class="payment-method-item" data-method="mtn">
                            <input type="radio" name="payment_method" id="mtn" class="payment-method-radio">
                            <div class="payment-method-logo">
                                <img src="{{ asset('images/mtn-money.png') }}" alt="MTN Money">
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">MTN Mobile Money</div>
                                <div class="payment-method-description">Pay using your MTN Mobile Money wallet</div>
                            </div>
                        </div>
                        
                        <!-- Pesapal -->
                        <div class="payment-method-item" data-method="pesapal">
                            <input type="radio" name="payment_method" id="pesapal" class="payment-method-radio">
                            <div class="payment-method-logo">
                                <img src="{{ asset('images/pesapal.png') }}" alt="Pesapal">
                            </div>
                            <div class="payment-method-info">
                                <div class="payment-method-name">Pesapal</div>
                                <div class="payment-method-description">Pay using Pesapal (M-Pesa, Airtel Money, and more)</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Money Sections -->
                    <div id="airtelPaymentSection" class="mobile-payment-section">
                        <div class="mobile-payment-header">
                            <div class="mobile-payment-logo">
                                <img src="{{ asset('images/airtel-money.png') }}" alt="Airtel Money">
                            </div>
                            <h4 class="mb-0">Airtel Money Payment</h4>
                        </div>
                        
                        <form id="airtelForm">
                            <div class="mobile-payment-form-group">
                                <label for="airtelPhoneNumber" class="mobile-payment-label">Phone Number</label>
                                <input type="tel" id="airtelPhoneNumber" class="mobile-payment-input" placeholder="Enter your Airtel Money number" required>
                                <div class="mobile-payment-help">Enter your Airtel Money registered phone number (e.g., +254XXXXXXXXX)</div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You will receive a prompt on your phone to complete the payment.
                            </div>
                        </form>
                    </div>
                    
                    <div id="mtnPaymentSection" class="mobile-payment-section">
                        <div class="mobile-payment-header">
                            <div class="mobile-payment-logo">
                                <img src="{{ asset('images/mtn-money.png') }}" alt="MTN Money">
                            </div>
                            <h4 class="mb-0">MTN Mobile Money Payment</h4>
                        </div>
                        
                        <form id="mtnForm">
                            <div class="mobile-payment-form-group">
                                <label for="mtnPhoneNumber" class="mobile-payment-label">Phone Number</label>
                                <input type="tel" id="mtnPhoneNumber" class="mobile-payment-input" placeholder="Enter your MTN Mobile Money number" required>
                                <div class="mobile-payment-help">Enter your MTN Mobile Money registered phone number (e.g., +256XXXXXXXXX)</div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You will receive a prompt on your phone to complete the payment.
                            </div>
                        </form>
                    </div>
                    
                    <div class="payment-buttons">
                        <a href="{{ route('streams.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button id="proceedButton" class="btn btn-primary">Proceed to Payment</button>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="payment-info">
                    <div class="payment-info-header">
                        <h3 class="payment-info-title">What's Included</h3>
                        <p class="mb-0">With your stream purchase, you'll get:</p>
                    </div>
                    
                    <div class="payment-info-item">
                        <span class="payment-info-label">Access Period</span>
                        <ul class="payment-feature-list">
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Live stream access during the event</span>
                            </li>
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>30-day replay access after the event</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="payment-info-item">
                        <span class="payment-info-label">Viewing Experience</span>
                        <ul class="payment-feature-list">
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Full HD & 4K quality streaming</span>
                            </li>
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Multi-camera angles</span>
                            </li>
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Professional commentary</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="payment-info-item">
                        <span class="payment-info-label">Additional Features</span>
                        <ul class="payment-feature-list">
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Live chat during the event</span>
                            </li>
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Watch on any device</span>
                            </li>
                            <li class="payment-feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Unlimited views during access period</span>
                            </li>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    <div class="payment-info-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="payment-info-label mb-0">Need Help?</span>
                            <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-primary">Contact Support</a>
                        </div>
                    </div>
                    
                    <div class="secure-badge">
                        <i class="fas fa-lock"></i> Secure Payment | Your data is protected
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Processing Modal -->
<div class="modal fade" id="processingModal" tabindex="-1" aria-labelledby="processingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-4" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Processing payment...</span>
                </div>
                <h4 id="processingModalLabel" class="mb-3">Processing Your Payment</h4>
                <p class="mb-0" id="processingMessage">Please wait while we process your payment...</p>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Money Instruction Modal -->
<div class="modal fade" id="mobileMoneyModal" tabindex="-1" aria-labelledby="mobileMoneyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mobileMoneyModalLabel">Complete Your Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div id="mobileMoneyLogo" class="mx-auto mb-3" style="width: 80px; height: 80px;">
                        <!-- Logo will be inserted here via JavaScript -->
                    </div>
                    <h4 id="mobileMoneyTitle">Mobile Money Payment</h4>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    <span id="mobileMoneyInstructions">Check your phone for a payment prompt to complete this transaction.</span>
                </div>
                
                <div class="mb-3">
                    <p class="fw-bold mb-2">Transaction Details:</p>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Amount:</span>
                        <span class="fw-bold" id="mobileMoneyAmount">${{ isset($stream) && isset($stream->price) ? number_format($stream->price * 1.05, 2) : '20.99' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phone Number:</span>
                        <span class="fw-bold" id="mobileMoneyPhone">+254XXXXXXXXX</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Reference:</span>
                        <span class="fw-bold">Stream #{{ isset($stream) ? $stream->id : rand(1000, 9999) }}</span>
                    </div>
                </div>
                
                <div class="progress mb-3">
                    <div id="paymentProgress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                </div>
                
                <p class="text-center text-muted small">If you don't receive a prompt within 60 seconds, please check your phone number and try again.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="checkStatusButton">Check Status</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment method selection
        const paymentMethodItems = document.querySelectorAll('.payment-method-item');
        const airtelPaymentSection = document.getElementById('airtelPaymentSection');
        const mtnPaymentSection = document.getElementById('mtnPaymentSection');
        
        paymentMethodItems.forEach(item => {
            item.addEventListener('click', function() {
                // Update radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update selected class
                paymentMethodItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                
                // Show/hide mobile money sections
                const method = this.dataset.method;
                airtelPaymentSection.classList.remove('active');
                mtnPaymentSection.classList.remove('active');
                
                if (method === 'airtel') {
                    airtelPaymentSection.classList.add('active');
                } else if (method === 'mtn') {
                    mtnPaymentSection.classList.add('active');
                }
            });
        });
        
        // Proceed to payment button
        const proceedButton = document.getElementById('proceedButton');
        const processingModal = new bootstrap.Modal(document.getElementById('processingModal'));
        const mobileMoneyModal = new bootstrap.Modal(document.getElementById('mobileMoneyModal'));
        
        proceedButton.addEventListener('click', function() {
            // Get selected payment method
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').id;
            
            // Validate mobile money forms if selected
            if (selectedMethod === 'airtel') {
                const phoneInput = document.getElementById('airtelPhoneNumber');
                if (!phoneInput.value) {
                    phoneInput.focus();
                    return;
                }
                
                // Show mobile money modal with Airtel branding
                document.getElementById('mobileMoneyLogo').innerHTML = '<img src="' + '{{ asset('images/airtel-money.png') }}' + '" alt="Airtel Money" class="img-fluid">';
                document.getElementById('mobileMoneyTitle').textContent = 'Airtel Money Payment';
                document.getElementById('mobileMoneyInstructions').textContent = 'Check your Airtel Money phone for a payment prompt to complete this transaction.';
                document.getElementById('mobileMoneyPhone').textContent = phoneInput.value;
                
                simulateMobileMoneyPayment(mobileMoneyModal);
                return;
            } else if (selectedMethod === 'mtn') {
                const phoneInput = document.getElementById('mtnPhoneNumber');
                if (!phoneInput.value) {
                    phoneInput.focus();
                    return;
                }
                
                // Show mobile money modal with MTN branding
                document.getElementById('mobileMoneyLogo').innerHTML = '<img src="' + '{{ asset('images/mtn-money.png') }}' + '" alt="MTN Money" class="img-fluid">';
                document.getElementById('mobileMoneyTitle').textContent = 'MTN Mobile Money Payment';
                document.getElementById('mobileMoneyInstructions').textContent = 'Check your MTN Mobile Money phone for a payment prompt to complete this transaction.';
                document.getElementById('mobileMoneyPhone').textContent = phoneInput.value;
                
                simulateMobileMoneyPayment(mobileMoneyModal);
                return;
            }
            
            // Show processing modal for other payment methods
            processingModal.show();
            
            // Update processing message based on payment method
            const processingMessage = document.getElementById('processingMessage');
            if (selectedMethod === 'credit_card') {
                processingMessage.textContent = 'Redirecting to secure payment page...';
                simulatePaymentRedirect('{{ route('payment.stripe', ['eventId' => isset($stream) ? $stream->id : 1]) }}');
            } else if (selectedMethod === 'paypal') {
                processingMessage.textContent = 'Redirecting to PayPal...';
                simulatePaymentRedirect('{{ route('payment.paypal', ['eventId' => isset($stream) ? $stream->id : 1]) }}');
            } else if (selectedMethod === 'pesapal') {
                processingMessage.textContent = 'Redirecting to Pesapal...';
                simulatePaymentRedirect('{{ route('payment.pesapal', ['eventId' => isset($stream) ? $stream->id : 1]) }}');
            }
        });
        
        // Simulate payment redirect
        function simulatePaymentRedirect(url) {
            setTimeout(() => {
                // In a real application, this would redirect to the payment processor
                // For demo purposes, we'll redirect to the stream page after a delay
                window.location.href = '{{ route('streams.show', ['stream' => isset($stream) ? $stream->id : 1]) }}';
            }, 3000);
        }
        
        // Simulate mobile money payment
        function simulateMobileMoneyPayment(modal) {
            modal.show();
            
            const progressBar = document.getElementById('paymentProgress');
            let progress = 0;
            
            const interval = setInterval(() => {
                progress += 20;
                progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    clearInterval(interval);
                    // In a real application, this would check the payment status
                    // For demo purposes, we'll redirect to the stream page after completing the progress
                    setTimeout(() => {
                        window.location.href = '{{ route('streams.show', ['stream' => isset($stream) ? $stream->id : 1]) }}';
                    }, 1000);
                }
            }, 1000);
            
            // Check status button
            document.getElementById('checkStatusButton').addEventListener('click', function() {
                progressBar.style.width = '100%';
                setTimeout(() => {
                    window.location.href = '{{ route('streams.show', ['stream' => isset($stream) ? $stream->id : 1]) }}';
                }, 1000);
            });
        }
    });
</script>
@endsection