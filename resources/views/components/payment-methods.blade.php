@props(['ticketId'])

<div class="payment-methods bg-white p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold mb-4">Select Payment Method</h3>
    
    <div class="flex flex-wrap gap-4 mb-6">
        <button
            id="stripe-button"
            class="payment-method-button bg-white border-2 border-gray-200 hover:border-sky-500 rounded-lg p-3 flex items-center"
            onclick="selectPaymentMethod('stripe')"
        >
            <img src="{{ asset('images/stripe-logo.svg') }}" alt="Stripe" class="h-8 mr-2">
            <span>Credit/Debit Card</span>
        </button>
        
        <button
            id="pesapal-button"
            class="payment-method-button bg-white border-2 border-gray-200 hover:border-sky-500 rounded-lg p-3 flex items-center"
            onclick="selectPaymentMethod('pesapal')"
        >
            <img src="{{ asset('images/pesapal-logo.svg') }}" alt="Pesapal" class="h-8 mr-2">
            <span>Pesapal</span>
        </button>
        
        <button
            id="airtel-button"
            class="payment-method-button bg-white border-2 border-gray-200 hover:border-sky-500 rounded-lg p-3 flex items-center"
            onclick="selectPaymentMethod('airtel')"
        >
            <img src="{{ asset('images/airtel-money-logo.svg') }}" alt="Airtel Money" class="h-8 mr-2">
            <span>Airtel Money</span>
        </button>
        
        <button
            id="mtn-button"
            class="payment-method-button bg-white border-2 border-gray-200 hover:border-sky-500 rounded-lg p-3 flex items-center"
            onclick="selectPaymentMethod('mtn')"
        >
            <img src="{{ asset('images/mtn-money-logo.svg') }}" alt="MTN Money" class="h-8 mr-2">
            <span>MTN Money</span>
        </button>
    </div>
    
    <div id="payment-forms" class="mt-4">
        <!-- Stripe Payment Form -->
        <div id="stripe-form" class="payment-form hidden">
            <h4 class="text-lg font-semibold mb-3">Pay with Credit/Debit Card</h4>
            <div id="stripe-card-element" class="bg-gray-50 p-4 rounded-md mb-4">
                <!-- Stripe will inject card element here -->
            </div>
            
            <div id="stripe-card-errors" class="text-red-500 mb-3"></div>
            
            <button 
                id="stripe-submit" 
                class="bg-sky-500 hover:bg-sky-600 text-white py-2 px-4 rounded disabled:bg-gray-300"
                disabled
            >
                <span id="stripe-button-text">Process Payment</span>
                <span id="stripe-spinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
        
        <!-- Pesapal Payment Form -->
        <div id="pesapal-form" class="payment-form hidden">
            <h4 class="text-lg font-semibold mb-3">Pay with Pesapal</h4>
            <p class="text-gray-600 mb-4">You will be redirected to Pesapal's secure payment page to complete your transaction.</p>
            
            <button 
                id="pesapal-submit" 
                class="bg-sky-500 hover:bg-sky-600 text-white py-2 px-4 rounded"
                onclick="processPesapalPayment()"
            >
                <span id="pesapal-button-text">Continue to Pesapal</span>
                <span id="pesapal-spinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
        
        <!-- Mobile Money Payment Forms -->
        <div id="mobile-money-form" class="payment-form hidden">
            <h4 class="text-lg font-semibold mb-3">Pay with Mobile Money</h4>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    class="w-full p-2 border border-gray-300 rounded-md" 
                    placeholder="Enter your mobile money number"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Enter your phone number in international format, e.g. +254712345678</p>
            </div>
            
            <div id="mobile-money-errors" class="text-red-500 mb-3"></div>
            
            <button 
                id="mobile-money-submit" 
                class="bg-sky-500 hover:bg-sky-600 text-white py-2 px-4 rounded"
                onclick="processMobileMoneyPayment()"
            >
                <span id="mobile-money-button-text">Process Payment</span>
                <span id="mobile-money-spinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </div>
</div>

<div id="payment-status" class="mt-6 p-4 rounded-lg hidden"></div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Ticket ID from props
    const ticketId = "{{ $ticketId }}";
    
    // Track selected payment method
    let selectedPaymentMethod = null;
    let mobilePaymentProvider = null;
    let stripeInstance = null;
    let cardElement = null;
    let paymentInProgress = false;
    
    // Initialize Stripe
    function initStripe() {
        if (!stripeInstance) {
            stripeInstance = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripeInstance.elements();
            
            cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        fontFamily: '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });
            
            cardElement.mount('#stripe-card-element');
            
            cardElement.on('change', function(event) {
                document.getElementById('stripe-card-errors').textContent = event.error ? event.error.message : '';
                document.getElementById('stripe-submit').disabled = event.empty;
            });
        }
    }
    
    // Select payment method
    function selectPaymentMethod(method) {
        // Reset UI
        document.querySelectorAll('.payment-method-button').forEach(btn => {
            btn.classList.remove('border-sky-500');
            btn.classList.add('border-gray-200');
        });
        
        document.querySelectorAll('.payment-form').forEach(form => {
            form.classList.add('hidden');
        });
        
        // Set selected payment method
        selectedPaymentMethod = method;
        document.getElementById(`${method}-button`).classList.remove('border-gray-200');
        document.getElementById(`${method}-button`).classList.add('border-sky-500');
        
        // Show appropriate form
        if (method === 'stripe') {
            document.getElementById('stripe-form').classList.remove('hidden');
            initStripe();
        } else if (method === 'pesapal') {
            document.getElementById('pesapal-form').classList.remove('hidden');
        } else if (method === 'airtel' || method === 'mtn') {
            document.getElementById('mobile-money-form').classList.remove('hidden');
            mobilePaymentProvider = method;
        }
    }
    
    // Process Stripe payment
    async function processStripePayment() {
        if (paymentInProgress) return;
        
        const submitButton = document.getElementById('stripe-submit');
        const buttonText = document.getElementById('stripe-button-text');
        const buttonSpinner = document.getElementById('stripe-spinner');
        const errorElement = document.getElementById('stripe-card-errors');
        
        try {
            paymentInProgress = true;
            
            // Update button state
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonSpinner.classList.remove('hidden');
            errorElement.textContent = '';
            
            // Create payment intent
            const response = await fetch('/payments/stripe/create-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ticket_id: ticketId
                })
            });
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Confirm card payment
            const result = await stripeInstance.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ auth()->user()->name }}'
                    }
                }
            });
            
            if (result.error) {
                throw new Error(result.error.message);
            } else if (result.paymentIntent.status === 'succeeded') {
                // Show success message
                showPaymentStatus('success', 'Payment successful! Your ticket has been confirmed.');
                
                // Redirect to confirmation page after delay
                setTimeout(() => {
                    window.location.href = `/tickets/${ticketId}/confirmation`;
                }, 2000);
            }
        } catch (error) {
            errorElement.textContent = error.message || 'An error occurred. Please try again.';
        } finally {
            paymentInProgress = false;
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
        }
    }
    
    // Process Pesapal payment
    async function processPesapalPayment() {
        if (paymentInProgress) return;
        
        const submitButton = document.getElementById('pesapal-submit');
        const buttonText = document.getElementById('pesapal-button-text');
        const buttonSpinner = document.getElementById('pesapal-spinner');
        
        try {
            paymentInProgress = true;
            
            // Update button state
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonSpinner.classList.remove('hidden');
            
            // Call Pesapal payment API
            const response = await fetch('/payments/pesapal/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ticket_id: ticketId
                })
            });
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Redirect to Pesapal page
            window.location.href = data.redirect_url;
            
        } catch (error) {
            showPaymentStatus('error', error.message || 'An error occurred. Please try again.');
            paymentInProgress = false;
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
        }
    }
    
    // Process Mobile Money payment (Airtel or MTN)
    async function processMobileMoneyPayment() {
        if (paymentInProgress) return;
        
        const submitButton = document.getElementById('mobile-money-submit');
        const buttonText = document.getElementById('mobile-money-button-text');
        const buttonSpinner = document.getElementById('mobile-money-spinner');
        const errorElement = document.getElementById('mobile-money-errors');
        const phoneInput = document.getElementById('phone');
        
        // Validate phone number
        if (!phoneInput.value) {
            errorElement.textContent = 'Please enter your phone number';
            return;
        }
        
        try {
            paymentInProgress = true;
            
            // Update button state
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonSpinner.classList.remove('hidden');
            errorElement.textContent = '';
            
            // Determine endpoint based on provider
            const endpoint = mobilePaymentProvider === 'airtel' 
                ? '/payments/airtel/process' 
                : '/payments/mtn/process';
            
            // Call mobile money payment API
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ticket_id: ticketId,
                    phone: phoneInput.value
                })
            });
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Show pending message
            showPaymentStatus('pending', 'Please check your phone to complete the payment. This page will automatically update when payment is confirmed.');
            
            // Start polling for payment status
            pollPaymentStatus(data.payment_id);
            
        } catch (error) {
            errorElement.textContent = error.message || 'An error occurred. Please try again.';
            paymentInProgress = false;
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
        }
    }
    
    // Poll payment status
    async function pollPaymentStatus(paymentId) {
        try {
            const response = await fetch(`/payments/status/${paymentId}`);
            const data = await response.json();
            
            if (data.status === 'completed') {
                showPaymentStatus('success', 'Payment confirmed! Your ticket has been processed successfully.');
                
                // Redirect to confirmation page after delay
                setTimeout(() => {
                    window.location.href = `/tickets/${ticketId}/confirmation`;
                }, 2000);
                
                return;
            } else if (data.status === 'failed') {
                showPaymentStatus('error', 'Payment failed. Please try again.');
                resetMobileMoneyForm();
                return;
            }
            
            // Continue polling if payment is still pending
            setTimeout(() => pollPaymentStatus(paymentId), 5000);
            
        } catch (error) {
            console.error('Error polling payment status:', error);
            // Continue polling despite error
            setTimeout(() => pollPaymentStatus(paymentId), 5000);
        }
    }
    
    // Show payment status
    function showPaymentStatus(type, message) {
        const statusElement = document.getElementById('payment-status');
        statusElement.innerHTML = `
            <div class="flex items-start">
                <div class="${type === 'success' ? 'bg-green-100 text-green-800' : type === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'} p-4 rounded-lg flex-1">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            ${type === 'success' 
                                ? '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                                : type === 'pending'
                                ? '<svg class="animate-spin h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                                : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                            }
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">
                                ${message}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        statusElement.classList.remove('hidden');
    }
    
    // Reset mobile money form
    function resetMobileMoneyForm() {
        const submitButton = document.getElementById('mobile-money-submit');
        const buttonText = document.getElementById('mobile-money-button-text');
        const buttonSpinner = document.getElementById('mobile-money-spinner');
        
        paymentInProgress = false;
        submitButton.disabled = false;
        buttonText.classList.remove('hidden');
        buttonSpinner.classList.add('hidden');
    }
    
    // Set up Stripe submit button
    document.getElementById('stripe-submit').addEventListener('click', function(e) {
        e.preventDefault();
        processStripePayment();
    });
    
    // Initialize with first payment method selected
    window.addEventListener('DOMContentLoaded', function() {
        selectPaymentMethod('stripe');
    });
</script>
@endpush