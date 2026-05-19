@extends('layouts.app')

@push('css')
<style>
    .checkout-card {
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid var(--zomato-border);
    }

    .checkout-input {
        height: 55px !important;
        border: 1px solid var(--zomato-border) !important;
        border-radius: 0.8rem !important;
        font-size: 1rem !important;
    }

    .checkout-input:focus {
        border-color: var(--zomato-red) !important;
        box-shadow: 0 0 0 0.2rem rgba(239, 79, 95, 0.1) !important;
    }

    .payment-option {
        border: 1px solid var(--zomato-border);
        border-radius: 1rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .payment-option.active {
        border-color: var(--zomato-red);
        background: rgba(239, 79, 95, 0.05);
    }

    .summary-card {
        border-radius: 1rem;
        padding: 1.5rem;
        background: #fff;
        border: 1px solid var(--zomato-border);
        position: sticky;
        top: 100px;
    }
</style>
@endpush

@section('content')

<div class="container py-5">

    <div class="row g-5">

        <div class="col-lg-8">

            <h2 class="fw-800 mb-4">
                Secure Checkout
            </h2>

            <form id="checkoutForm"
                  action="{{ route('checkout.process') }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="distance"
                       id="dist_val"
                       value="-1">

                <div class="checkout-card mb-4 bg-white">

                    <h5 class="fw-bold mb-4">
                        Delivery Details
                    </h5>

                    <div class="mb-4">

                        <label class="form-label small fw-bold text-muted text-uppercase">
                            Delivery Address
                        </label>

                        <div class="input-group">

                            <input type="text"
                                   name="address"
                                   id="addressInput"
                                   class="form-control checkout-input"
                                   placeholder="Enter your full address"
                                   required>

                            <button class="btn btn-zomato px-4"
                                    type="button"
                                    onclick="runCalculation()">

                                <span id="btnText">
                                    Check Fee
                                </span>

                                <span id="btnSpin" class="d-none">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>

                            </button>

                        </div>

                        <div id="fee-status"
                             class="mt-2 small d-none">
                        </div>

                    </div>

                    <div class="mb-0">

                        <label class="form-label small fw-bold text-muted text-uppercase">
                            Mobile Number
                        </label>

                        <input type="text"
                               name="phone"
                               id="phoneInput"
                               class="form-control checkout-input"
                               placeholder="10-digit number"
                               maxlength="10"
                               required>

                    </div>

                </div>

                <div class="checkout-card bg-white">

                    <h5 class="fw-bold mb-4">
                        Payment Method
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="payment-option active"
                                 id="optCOD"
                                 onclick="selectPay('COD')">

                                <input type="radio"
                                       name="payment_method"
                                       value="COD"
                                       id="radioCOD"
                                       class="d-none"
                                       checked>

                                <div class="bg-light rounded-circle p-2">
                                    <i class="fas fa-money-bill-wave text-success fs-4"></i>
                                </div>

                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        Cash on Delivery
                                    </h6>

                                    <p class="mb-0 small text-muted">
                                        Pay when you receive
                                    </p>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="payment-option"
                                 id="optOnline"
                                 onclick="selectPay('Online')">

                                <input type="radio"
                                       name="payment_method"
                                       value="Online"
                                       id="radioOnline"
                                       class="d-none">

                                <div class="bg-light rounded-circle p-2">
                                    <i class="fas fa-credit-card text-primary fs-4"></i>
                                </div>

                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        Online Payment
                                    </h6>

                                    <p class="mb-0 small text-muted">
                                        Cards, UPI, Netbanking
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

        <div class="col-lg-4">

            <div class="summary-card shadow-sm">

                <h5 class="fw-bold mb-4">
                    Order Summary
                </h5>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Item Total</span>
                    <span class="fw-bold">
                        ₹{{ number_format($subtotal, 2) }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3 text-success">
                    <span class="small">Taxes & Charges (5%)</span>
                    <span class="small">
                        ₹{{ number_format($subtotal * 0.05, 2) }}
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Delivery Fee</span>
                    <span id="label-del" class="fw-bold">
                        ₹ --
                    </span>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between mb-4 h4 fw-800">
                    <span>Total</span>
                    <span id="label-total" class="text-danger">
                        ₹ --
                    </span>
                </div>

                <button type="button"
                        onclick="finalize()"
                        class="btn btn-zomato w-100 py-3 rounded-3 shadow-sm fw-bold">

                    Place Your Order

                </button>

            </div>

        </div>

    </div>

</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    const subtotal = {{ $subtotal }};
    let payMode = 'COD';

    async function runCalculation() {

        const userInput =
            document.getElementById('addressInput').value.trim();

        if (userInput.length < 3) {

            return Swal.fire(
                'Error',
                'Please enter your address.',
                'error'
            );
        }

        const btnT =
            document.getElementById('btnText');

        const btnS =
            document.getElementById('btnSpin');

        btnT.classList.add('d-none');
        btnS.classList.remove('d-none');

        try {

            const response = await fetch(
                '/check-distance',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN':
                            '{{ csrf_token() }}'
                    },

                    body: JSON.stringify({
                        address: userInput
                    })
                }
            );

            const res = await response.json();

            btnT.classList.remove('d-none');
            btnS.classList.add('d-none');

            console.log(res);

            if (res.success) {

                applyFee(res.distance);

            } else {

                Swal.fire(
                    'Error',
                    res.message || 'Unable to calculate distance',
                    'error'
                );
            }

        } catch (e) {

            console.log(e);

            btnT.classList.remove('d-none');
            btnS.classList.add('d-none');

            Swal.fire(
                'Error',
                'Server request failed',
                'error'
            );
        }
    }

    function applyFee(km) {

        document.getElementById('dist_val').value =
            km.toFixed(2);

        let fee =
            Math.max(20, Math.ceil(km) * 10);

        const tax = subtotal * 0.05;

        const total =
            subtotal + fee + tax;

        document.getElementById('label-del').innerText =
            '₹ ' + fee.toFixed(2);

        document.getElementById('label-total').innerText =
            '₹ ' + total.toFixed(2);

        const pill =
            document.getElementById('fee-status');

        pill.classList.remove('d-none');

        pill.className =
            'mt-2 small text-success fw-bold';

        pill.innerText =
            `Confirmed: ${km.toFixed(1)} km distance`;
    }

    function selectPay(mode) {

        payMode = mode;

        document.getElementById('radioCOD').checked =
            (mode === 'COD');

        document.getElementById('radioOnline').checked =
            (mode === 'Online');

        document.getElementById('optCOD')
            .classList.toggle(
                'active',
                mode === 'COD'
            );

        document.getElementById('optOnline')
            .classList.toggle(
                'active',
                mode === 'Online'
            );
    }

    function finalize() {

        const addr =
            document.getElementById('addressInput')
                .value.trim();

        const ph =
            document.getElementById('phoneInput')
                .value.trim();

        const dist =
            document.getElementById('dist_val').value;

        if (addr.length < 5) {

            return Swal.fire(
                'Error',
                'Please enter full address',
                'error'
            );
        }

        if (!/^[0-9]{10}$/.test(ph)) {

            return Swal.fire(
                'Error',
                'Please enter valid mobile number',
                'error'
            );
        }

        if (dist == "-1") {

            runCalculation();

            return;
        }

        const form =
            document.getElementById('checkoutForm');

        if (payMode === 'Online') {

            const amount =
                parseFloat(
                    document.getElementById('label-total')
                        .innerText
                        .replace('₹ ', '')
                );

            const options = {

                key: "{{ env('RAZORPAY_KEY') }}",

                amount:
                    Math.round(amount * 100),

                currency: "INR",

                name: "Sip N Bite",

                description:
                    "Food Order Payment",

                handler: function (response) {

                    let input =
                        document.createElement("input");

                    input.type = "hidden";

                    input.name =
                        "razorpay_payment_id";

                    input.value =
                        response.razorpay_payment_id;

                    form.appendChild(input);

                    form.submit();
                },

                prefill: {
                    name: "{{ Auth::user()->name }}",
                    email: "{{ Auth::user()->email }}"
                },

                theme: {
                    color: "#ef4f5f"
                }
            };

            const rzp =
                new Razorpay(options);

            rzp.open();

        } else {

            form.submit();
        }
    }

    document.getElementById('addressInput')
        .addEventListener('input', () => {

            document.getElementById('dist_val').value = "-1";

            document.getElementById('label-del').innerText =
                '₹ --';

            document.getElementById('label-total').innerText =
                '₹ --';

            document.getElementById('fee-status')
                .classList.add('d-none');
        });

</script>

@endsection