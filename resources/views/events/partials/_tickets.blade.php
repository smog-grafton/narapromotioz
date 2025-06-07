<!-- Tickets Section -->
<section id="tickets" class="tickets-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">TICKETS</h2>
            <p class="section-subtitle">
                @if($event->isUpcoming)
                    Get Your Tickets for {{ $event->name }}
                @else
                    Tickets No Longer Available
                @endif
            </p>
        </div>
        
        @if($event->tickets_available && $event->isUpcoming)
            @php
                $tickets = $event->tickets()->where('is_active', true)->get();
            @endphp
            
            @if($tickets->isNotEmpty())
                <div class="row">
                    <div class="col-lg-8">
                        <div class="tickets-container">
                            @foreach($tickets as $ticket)
                                <div class="ticket-card">
                                    <div class="ticket-header">
                                        <h3 class="ticket-name">{{ $ticket->name }}</h3>
                                        <div class="ticket-price">${{ number_format($ticket->price, 2) }}</div>
                                    </div>
                                    
                                    <div class="ticket-body">
                                        <div class="ticket-description">
                                            {{ $ticket->description }}
                                        </div>
                                        
                                        @if($ticket->benefits)
                                            <div class="ticket-benefits">
                                                <h4>Benefits</h4>
                                                <ul>
                                                    @foreach(json_decode($ticket->benefits, true) as $benefit)
                                                        <li><i class="fas fa-check"></i> {{ $benefit }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <div class="ticket-details">
                                            <div class="detail-row">
                                                <span class="detail-label">Type:</span>
                                                <span class="detail-value">{{ ucfirst($ticket->ticket_type) }}</span>
                                            </div>
                                            
                                            @if($ticket->seating_info)
                                                <div class="detail-row">
                                                    <span class="detail-label">Seating:</span>
                                                    <span class="detail-value">{{ $ticket->seating_info }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($ticket->quantity_available > 0)
                                                <div class="detail-row">
                                                    <span class="detail-label">Available:</span>
                                                    <span class="detail-value">{{ $ticket->quantity_available - $ticket->quantity_sold }} tickets</span>
                                                </div>
                                            @endif
                                            
                                            @if($ticket->sale_ends_at)
                                                <div class="detail-row">
                                                    <span class="detail-label">Sale Ends:</span>
                                                    <span class="detail-value">{{ Carbon\Carbon::parse($ticket->sale_ends_at)->format('F j, Y, g:i A') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="ticket-footer">
                                        @if($ticket->quantity_available > $ticket->quantity_sold)
                                            <button class="btn btn-primary buy-ticket-btn" data-ticket-id="{{ $ticket->id }}" data-ticket-name="{{ $ticket->name }}" data-ticket-price="{{ $ticket->price }}">
                                                <i class="fas fa-ticket-alt"></i> BUY NOW
                                            </button>
                                        @else
                                            <button class="btn btn-sold-out" disabled>
                                                <i class="fas fa-times-circle"></i> SOLD OUT
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="checkout-card" id="checkoutCard" style="display: none;">
                            <div class="checkout-header">
                                <h3>Your Order</h3>
                            </div>
                            
                            <div class="checkout-body">
                                <div class="checkout-item">
                                    <span class="item-name" id="checkoutTicketName"></span>
                                    <span class="item-price" id="checkoutTicketPrice"></span>
                                </div>
                                
                                <div class="quantity-selector">
                                    <label for="ticketQuantity">Quantity:</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn minus">-</button>
                                        <input type="number" id="ticketQuantity" class="quantity-input" min="1" max="10" value="1">
                                        <button type="button" class="quantity-btn plus">+</button>
                                    </div>
                                </div>
                                
                                <div class="checkout-summary">
                                    <div class="summary-row">
                                        <span>Subtotal:</span>
                                        <span id="checkoutSubtotal"></span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Service Fee:</span>
                                        <span id="checkoutFee"></span>
                                    </div>
                                    <div class="summary-row total">
                                        <span>Total:</span>
                                        <span id="checkoutTotal"></span>
                                    </div>
                                </div>
                                
                                <div class="checkout-form">
                                    <div class="form-group">
                                        <label for="checkoutEmail">Email:</label>
                                        <input type="email" id="checkoutEmail" class="form-control" placeholder="Enter your email" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                    </div>
                                    
                                    @if(!auth()->check())
                                        <div class="login-prompt">
                                            <p>Already have an account? <a href="{{ route('login') }}?redirect={{ route('events.show', $event->slug) }}">Log in</a> for faster checkout.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="checkout-footer">
                                <button type="button" class="btn btn-outline" id="cancelCheckout">CANCEL</button>
                                <button type="button" class="btn btn-primary" id="proceedToPayment">CHECKOUT</button>
                            </div>
                        </div>
                        
                        <div class="event-info-card">
                            <div class="event-info-header">
                                <h3>Event Information</h3>
                            </div>
                            
                            <div class="event-info-body">
                                <div class="info-row">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <span class="info-label">Date</span>
                                        <span class="info-value">{{ $event->event_date->format('F j, Y') }}</span>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <span class="info-label">Time</span>
                                        <span class="info-value">{{ $event->event_time ? $event->event_time->format('g:i A') : 'TBA' }}</span>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <span class="info-label">Venue</span>
                                        <span class="info-value">{{ $event->venue }}</span>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-map-pin"></i>
                                    <div>
                                        <span class="info-label">Location</span>
                                        <span class="info-value">{{ $event->city }}, {{ $event->country }}</span>
                                    </div>
                                </div>
                                
                                @if($event->address)
                                    <div class="venue-map">
                                        <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ urlencode($event->address) }}&zoom=14&size=400x200&markers=color:red|{{ urlencode($event->address) }}&key=YOUR_GOOGLE_MAPS_API_KEY" alt="Venue Map" class="img-fluid">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3 class="empty-title">No Tickets Available</h3>
                    <p class="empty-description">Tickets for this event are not yet available for purchase. Please check back later.</p>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="empty-title">
                    @if($event->isUpcoming)
                        Tickets Coming Soon
                    @else
                        Ticket Sales Closed
                    @endif
                </h3>
                <p class="empty-description">
                    @if($event->isUpcoming)
                        Tickets for this event are not yet available for purchase. Please check back later.
                    @else
                        Ticket sales for this event have ended. The event has already taken place.
                    @endif
                </p>
            </div>
        @endif
    </div>
</section>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Complete Your Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-summary">
                    <h4>Order Summary</h4>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span class="summary-label">Event:</span>
                            <span class="summary-value">{{ $event->name }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Ticket:</span>
                            <span class="summary-value" id="modalTicketName"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Quantity:</span>
                            <span class="summary-value" id="modalTicketQuantity"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Total:</span>
                            <span class="summary-value" id="modalTotalPrice"></span>
                        </div>
                    </div>
                </div>
                
                <div class="payment-options">
                    <h4>Payment Method</h4>
                    <div class="payment-tabs">
                        <div class="payment-tab active" data-target="creditCardForm">Credit Card</div>
                        <div class="payment-tab" data-target="pesapalForm">Pesapal</div>
                    </div>
                    
                    <div class="payment-tab-content">
                        <div id="creditCardForm" class="payment-form active">
                            <div class="form-group">
                                <label for="cardName">Name on Card</label>
                                <input type="text" id="cardName" class="form-control" placeholder="John Doe">
                            </div>
                            
                            <div class="form-group">
                                <label for="cardNumber">Card Number</label>
                                <input type="text" id="cardNumber" class="form-control" placeholder="1234 5678 9012 3456">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cardExpiry">Expiration Date</label>
                                        <input type="text" id="cardExpiry" class="form-control" placeholder="MM/YY">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cardCvv">CVV</label>
                                        <input type="text" id="cardCvv" class="form-control" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="pesapalForm" class="payment-form">
                            <p>You will be redirected to Pesapal to complete your payment securely.</p>
                            <div class="pesapal-logo">
                                <img src="{{ asset('assets/images/payments/pesapal-logo.png') }}" alt="Pesapal" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-primary" id="completePayment">COMPLETE PAYMENT</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .tickets-section {
        padding: 5rem 0;
        background: $dark-bg;
        color: $text-light;
    }
    
    .ticket-card {
        background: #1c1c1c;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 2rem;
    }
    
    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .ticket-name {
        font-family: $font-family-heading;
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        margin: 0;
    }
    
    .ticket-price {
        font-size: 1.75rem;
        font-weight: 700;
        color: $theme-red;
    }
    
    .ticket-body {
        padding: 1.5rem;
    }
    
    .ticket-description {
        color: $text-gray;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .ticket-benefits {
        margin-bottom: 1.5rem;
        
        h4 {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }
        
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            
            li {
                margin-bottom: 0.5rem;
                display: flex;
                align-items: flex-start;
                
                i {
                    color: $theme-green;
                    margin-right: 0.5rem;
                    margin-top: 0.25rem;
                }
            }
        }
    }
    
    .ticket-details {
        .detail-row {
            display: flex;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            
            .detail-label {
                color: $text-gray;
                width: 100px;
                flex-shrink: 0;
            }
            
            .detail-value {
                color: $text-light;
            }
        }
    }
    
    .ticket-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
        text-align: right;
        
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            
            i {
                margin-right: 0.5rem;
            }
            
            &.btn-sold-out {
                background-color: $theme-gray;
                color: $text-light;
                cursor: not-allowed;
            }
        }
    }
    
    .checkout-card {
        background: #1c1c1c;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 2rem;
    }
    
    .checkout-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        
        h3 {
            font-family: $font-family-heading;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
        }
    }
    
    .checkout-body {
        padding: 1.5rem;
    }
    
    .checkout-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        
        .item-name {
            font-weight: 600;
            color: $text-light;
        }
        
        .item-price {
            color: $theme-red;
            font-weight: 700;
        }
    }
    
    .quantity-selector {
        margin-bottom: 1.5rem;
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: $text-gray;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            
            .quantity-btn {
                width: 36px;
                height: 36px;
                background: rgba(255, 255, 255, 0.1);
                border: none;
                color: $text-light;
                font-size: 1.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: $transition-fast;
                
                &:hover {
                    background: rgba(255, 255, 255, 0.2);
                }
            }
            
            .quantity-input {
                width: 60px;
                height: 36px;
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: $text-light;
                text-align: center;
                margin: 0 0.5rem;
                
                &::-webkit-inner-spin-button,
                &::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }
            }
        }
    }
    
    .checkout-summary {
        background: rgba(255, 255, 255, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            color: $text-gray;
            
            &.total {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 1.1rem;
                font-weight: 700;
                color: $text-light;
            }
        }
    }
    
    .checkout-form {
        .form-group {
            margin-bottom: 1.5rem;
            
            label {
                display: block;
                margin-bottom: 0.5rem;
                color: $text-gray;
            }
            
            .form-control {
                width: 100%;
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: $text-light;
                padding: 0.75rem;
                transition: $transition-fast;
                
                &:focus {
                    border-color: $theme-red;
                    outline: none;
                }
            }
        }
        
        .login-prompt {
            font-size: 0.9rem;
            color: $text-gray;
            
            a {
                color: $theme-red;
                text-decoration: none;
                
                &:hover {
                    text-decoration: underline;
                }
            }
        }
    }
    
    .checkout-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            
            &.btn-outline {
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: $text-light;
                
                &:hover {
                    background: rgba(255, 255, 255, 0.1);
                }
            }
        }
    }
    
    .event-info-card {
        background: #1c1c1c;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .event-info-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        
        h3 {
            font-family: $font-family-heading;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
        }
    }
    
    .event-info-body {
        padding: 1.5rem;
        
        .info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.25rem;
            
            i {
                color: $theme-red;
                font-size: 1.25rem;
                margin-right: 1rem;
                width: 20px;
                text-align: center;
            }
            
            div {
                flex: 1;
                
                .info-label {
                    display: block;
                    color: $text-gray;
                    font-size: 0.9rem;
                    margin-bottom: 0.25rem;
                }
                
                .info-value {
                    color: $text-light;
                    font-weight: 500;
                }
            }
        }
        
        .venue-map {
            margin-top: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
    }
    
    /* Payment Modal Styles */
    .modal-content {
        background: #1c1c1c;
        color: $text-light;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        
        .modal-title {
            font-family: $font-family-heading;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .btn-close {
            color: $text-light;
            opacity: 0.8;
            
            &:hover {
                opacity: 1;
            }
        }
    }
    
    .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .payment-summary {
        margin-bottom: 2rem;
        
        h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-size: 1.1rem;
        }
        
        .summary-details {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            
            .summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.75rem;
                
                &:last-child {
                    margin-bottom: 0;
                    padding-top: 1rem;
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                    font-weight: 700;
                }
                
                .summary-label {
                    color: $text-gray;
                }
            }
        }
    }
    
    .payment-options {
        h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-size: 1.1rem;
        }
        
        .payment-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            
            .payment-tab {
                padding: 0.75rem 1.5rem;
                background: rgba(255, 255, 255, 0.1);
                cursor: pointer;
                transition: $transition-fast;
                
                &:hover {
                    background: rgba(255, 255, 255, 0.2);
                }
                
                &.active {
                    background: $theme-red;
                    color: $text-light;
                }
            }
        }
        
        .payment-form {
            display: none;
            
            &.active {
                display: block;
            }
            
            .form-group {
                margin-bottom: 1.5rem;
                
                label {
                    display: block;
                    margin-bottom: 0.5rem;
                    color: $text-gray;
                }
                
                .form-control {
                    width: 100%;
                    background: transparent;
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    color: $text-light;
                    padding: 0.75rem;
                    transition: $transition-fast;
                    
                    &:focus {
                        border-color: $theme-red;
                        outline: none;
                    }
                }
            }
            
            .pesapal-logo {
                text-align: center;
                margin-top: 1.5rem;
                
                img {
                    max-width: 200px;
                }
            }
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buy ticket button click
        const buyTicketBtns = document.querySelectorAll('.buy-ticket-btn');
        const checkoutCard = document.getElementById('checkoutCard');
        const checkoutTicketName = document.getElementById('checkoutTicketName');
        const checkoutTicketPrice = document.getElementById('checkoutTicketPrice');
        const ticketQuantity = document.getElementById('ticketQuantity');
        const checkoutSubtotal = document.getElementById('checkoutSubtotal');
        const checkoutFee = document.getElementById('checkoutFee');
        const checkoutTotal = document.getElementById('checkoutTotal');
        const cancelCheckout = document.getElementById('cancelCheckout');
        
        let currentTicketId = null;
        let currentTicketPrice = 0;
        
        buyTicketBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                currentTicketId = this.getAttribute('data-ticket-id');
                const ticketName = this.getAttribute('data-ticket-name');
                currentTicketPrice = parseFloat(this.getAttribute('data-ticket-price'));
                
                checkoutTicketName.textContent = ticketName;
                checkoutTicketPrice.textContent = '$' + currentTicketPrice.toFixed(2);
                
                updateCheckoutTotals();
                checkoutCard.style.display = 'block';
                
                // Scroll to checkout card
                checkoutCard.scrollIntoView({ behavior: 'smooth' });
            });
        });
        
        // Quantity controls
        const minusBtn = document.querySelector('.quantity-btn.minus');
        const plusBtn = document.querySelector('.quantity-btn.plus');
        
        minusBtn.addEventListener('click', function() {
            if (parseInt(ticketQuantity.value) > 1) {
                ticketQuantity.value = parseInt(ticketQuantity.value) - 1;
                updateCheckoutTotals();
            }
        });
        
        plusBtn.addEventListener('click', function() {
            if (parseInt(ticketQuantity.value) < parseInt(ticketQuantity.max)) {
                ticketQuantity.value = parseInt(ticketQuantity.value) + 1;
                updateCheckoutTotals();
            }
        });
        
        ticketQuantity.addEventListener('change', function() {
            updateCheckoutTotals();
        });
        
        function updateCheckoutTotals() {
            const quantity = parseInt(ticketQuantity.value);
            const subtotal = currentTicketPrice * quantity;
            const fee = subtotal * 0.05; // 5% service fee
            const total = subtotal + fee;
            
            checkoutSubtotal.textContent = '$' + subtotal.toFixed(2);
            checkoutFee.textContent = '$' + fee.toFixed(2);
            checkoutTotal.textContent = '$' + total.toFixed(2);
        }
        
        // Cancel checkout
        cancelCheckout.addEventListener('click', function() {
            checkoutCard.style.display = 'none';
        });
        
        // Proceed to payment
        const proceedToPayment = document.getElementById('proceedToPayment');
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        const modalTicketName = document.getElementById('modalTicketName');
        const modalTicketQuantity = document.getElementById('modalTicketQuantity');
        const modalTotalPrice = document.getElementById('modalTotalPrice');
        
        proceedToPayment.addEventListener('click', function() {
            modalTicketName.textContent = checkoutTicketName.textContent;
            modalTicketQuantity.textContent = ticketQuantity.value;
            modalTotalPrice.textContent = checkoutTotal.textContent;
            
            paymentModal.show();
        });
        
        // Payment tabs
        const paymentTabs = document.querySelectorAll('.payment-tab');
        
        paymentTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                paymentTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Get the target content id
                const targetId = this.getAttribute('data-target');
                
                // Hide all content panes
                document.querySelectorAll('.payment-form').forEach(form => form.classList.remove('active'));
                
                // Show the selected content pane
                document.getElementById(targetId).classList.add('active');
            });
        });
        
        // Complete payment
        const completePayment = document.getElementById('completePayment');
        
        completePayment.addEventListener('click', function() {
            // Here you would implement the actual payment processing
            // For now, we'll just show a success message
            
            paymentModal.hide();
            
            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'alert alert-success mt-3';
            successMessage.innerHTML = '<strong>Success!</strong> Your payment has been processed successfully. An email with your ticket details has been sent to your email address.';
            
            checkoutCard.insertAdjacentElement('afterend', successMessage);
            
            // Hide checkout card
            checkoutCard.style.display = 'none';
            
            // Scroll to success message
            successMessage.scrollIntoView({ behavior: 'smooth' });
            
            // Remove success message after 5 seconds
            setTimeout(() => {
                successMessage.remove();
            }, 5000);
        });
    });
</script>
@endpush 