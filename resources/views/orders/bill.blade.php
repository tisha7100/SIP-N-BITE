<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $bill->bill_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f0f7ff; }
        .bill-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(37,99,235,0.10);
            max-width: 820px;
            margin: 40px auto;
            padding: 50px;
        }
        .bill-header-bar {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 1rem 1rem 0 0;
            padding: 18px 30px;
            color: white;
            max-width: 820px;
            margin: 40px auto 0;
        }
        .brand-name { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.5px; }
        .invoice-label { font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; opacity: 0.8; }
        .bill-table thead th {
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            padding: 12px 14px;
        }
        .bill-table tbody td { padding: 12px 14px; border-color: #e5e7eb; vertical-align: top; }
        .bill-table tbody tr:last-child td { border-bottom: 2px solid #dbeafe; }
        .addon-pill {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 50px;
            padding: 2px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            margin: 2px 0;
        }
        .summary-box { background: #f8fafc; border-radius: 1rem; padding: 20px 24px; }
        .text-blue { color: #2563eb !important; }
        .divider { border-color: #dbeafe; }
        @media print {
            body { background: white; }
            .bill-header-bar { margin-top: 0; }
            .bill-card { box-shadow: none; margin: 0; max-width: 100%; padding: 20px; border-radius: 0; }
            .btn-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container py-2 btn-print text-center mt-3">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">
        <i class="fas fa-arrow-left me-2"></i> Back to Orders
    </a>
    <button onclick="window.print()" class="btn rounded-pill px-4" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;">
        <i class="fas fa-print me-2"></i> Print / Download PDF
    </button>
</div>

<!-- Header bar -->
<div class="bill-header-bar d-flex justify-content-between align-items-center">
    <div>
        <div class="brand-name"><i class="fas fa-utensils me-2"></i>Sip N Bite</div>
        <div class="invoice-label">Pure Veg Restaurant &amp; Delivery</div>
    </div>
    <div class="text-end">
        <div class="invoice-label">Invoice</div>
        <div style="font-size:1.2rem;font-weight:800;">#{{ $bill->bill_number }}</div>
    </div>
</div>

<div class="bill-card" style="border-radius: 0 0 1.5rem 1.5rem; margin-top:0;">

    <!-- Restaurant + Customer info -->
    <div class="row mb-4 pt-2">
        <div class="col-md-6">
            <p class="text-muted mb-1" style="font-size:0.75rem;text-transform:uppercase;font-weight:700;letter-spacing:.1em;">Restaurant</p>
            <p class="mb-0 small text-muted"><i class="fas fa-map-marker-alt me-1 text-blue"></i> Athwa, Surat, Gujarat</p>
            <p class="mb-0 small text-muted"><i class="fas fa-phone me-1 text-blue"></i> +91 93275 62890</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <p class="text-muted mb-1" style="font-size:0.75rem;text-transform:uppercase;font-weight:700;letter-spacing:.1em;">Bill To</p>
            <h6 class="fw-bold mb-0">{{ $order->user->name }}</h6>
            <p class="mb-0 small text-muted">{{ $order->shipping_address }}</p>
            <p class="mb-0 small text-muted"><i class="fas fa-phone me-1"></i> {{ $order->phone }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <span class="text-muted small">Date: </span>
            <strong class="small">{{ $bill->created_at->format('d M Y, h:i A') }}</strong>
        </div>
        <div class="col-6 text-end">
            <span class="text-muted small">Payment: </span>
            <span class="muted small text-blue">{{ $order->payment_method }}</span>
            &nbsp;
            @if(strtolower($order->payment_method) != 'cod')
                <span class="badge rounded-pill px-3" style="background:#dbeafe;color:#1d4ed8;font-size:0.7rem;">PAID</span>
            @endif
        </div>
    </div>

    <hr class="divider mb-4">

    <!-- Items table -->
    <div class="table-responsive mb-4">
        <table class="table bill-table align-middle">
            <thead>
                <tr>
                    <th style="width:40px;">SR</th>
                    <th>Item Name</th>
                    <th style="min-width:140px;">Add-Ons</th>
                    <th class="text-center" style="width:60px;">Qty</th>
                    <th class="text-end" style="width:90px;">Price</th>
                    <th class="text-end" style="width:100px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $index => $item)
                @php
                    $addonTotal = $item->addons->sum('price');
                    $unitPrice  = $item->price + $addonTotal;
                    $rowTotal   = $unitPrice * $item->quantity;
                @endphp
                <tr>
                    <td class="text-muted">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $item->food->name }}</td>
                    <td>
                        @forelse($item->addons as $oa)
                            <div class="addon-pill">{{ $oa->addon->name }} <span class="ms-1 opacity-75">+₹{{ number_format($oa->price, 0) }}</span></div><br>
                        @empty
                            <span class="text-muted small">—</span>
                        @endforelse
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end text-muted small">
                        ₹{{ number_format($item->price, 2) }}
                        @if($addonTotal > 0)
                            <div style="font-size:0.7rem;color:#2563eb;">+₹{{ number_format($addonTotal, 2) }}</div>
                        @endif
                    </td>
                    <td class="text-end fw-bold text-blue">₹{{ number_format($rowTotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="row justify-content-end">
        <div class="col-md-5">
            <div class="summary-box">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Subtotal:</span>
                    <span class="fw-bold">₹{{ number_format($bill->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">GST (5%):</span>
                    <span class="fw-bold">₹{{ number_format($bill->gst, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Delivery Charge:</span>
                    <span class="fw-bold text-blue">₹{{ number_format($bill->delivery_charge, 2) }}</span>
                </div>
                <hr class="divider">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Total Amount:</h5>
                    <h4 class="fw-bold text-blue mb-0">₹{{ number_format($bill->final_amount, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center border-top pt-4">
        <p class="text-muted small mb-1"><i class="fas fa-heart text-blue me-1"></i> Thank you for ordering from <strong>Sip N Bite</strong>!</p>
        <p class="text-muted" style="font-size:0.7rem;">This is a system-generated invoice and does not require a signature.</p>
    </div>
</div>

</body>
</html>
