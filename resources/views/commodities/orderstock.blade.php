@extends('layouts.app')

@section('title', 'Order Stock')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><i class="fas fa-cart-plus me-2"></i>Order New Stock</h3>
                            <p class="mb-0">Generate stock order form</p>
                        </div>
                        <a href="{{ route('commodities.fsindex') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back to Commodities
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Validation Errors</h4>
                            <p>Please fix the following errors:</p>
                            <hr>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('commodities.generate-order-pdf') }}" method="POST" id="orderStockForm">
                        @csrf
                        
                        <!-- Order Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="order_date" class="form-label">Order Date</label>
                                <input type="date" class="form-control" id="order_date" name="order_date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="requested_by" class="form-label">Requested By</label>
                                <input type="text" class="form-control" id="requested_by" name="requested_by" 
                                       value="{{ auth()->user()->name }}" required>
                            </div>
                        </div>

                        <!-- Items to Order -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-boxes me-2"></i>Items to Order</h5>
                                <div id="itemsContainer">
                                    <div class="item-row border rounded p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Item Name</label>
                                                <select class="form-select item-name-select" name="items[0][name]" required>
                                                    <option value="">Select Commodity</option>
                                                    @if(!empty($commodities))
                                                        @foreach($commodities as $commodity)
                                                            <option value="{{ $commodity->name }}" data-metric="{{ $commodity->metric }}">{{ $commodity->name }} ({{ $commodity->quantity }} {{ $commodity->metric }})</option>
                                                        @endforeach
                                                    @else
                                                        <option value="" disabled>No commodities available</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="items[0][quantity]" min="1" required>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label">Unit</label>
                                                <input type="text" class="form-control unit-input" name="items[0][unit]" value="" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Description/Specifications</label>
                                                <textarea class="form-control" name="items[0][description]" rows="2" placeholder="Additional details about the item"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" id="addItemBtn"><i class="fas fa-plus me-2"></i>Add Item</button>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="urgency" class="form-label">Urgency Level</label>
                                        <select class="form-select" id="urgency" name="urgency" required>
                                            <option value="">Select Urgency</option>
                                            <option value="Low">Low - Normal ordering</option>
                                            <option value="Medium">Medium - Needed within 1-2 weeks</option>
                                            <option value="High">High - Needed urgently</option>
                                            <option value="Critical">Critical - Immediate need</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expected_delivery" class="form-label">Expected Delivery Date</label>
                                        <input type="date" class="form-control" id="expected_delivery" name="expected_delivery">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                        <i class="fas fa-times-circle me-2"></i>Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-file-pdf me-2"></i>Generate PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let itemCounter = 1;

function updateUnit(selectElem) {
    const selectedOption = selectElem.options[selectElem.selectedIndex];
    const metric = selectedOption.getAttribute('data-metric') || '';
    const unitInput = selectElem.closest('.row').querySelector('.unit-input');
    if (unitInput) unitInput.value = metric;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initial unit update for first row
    const firstSelect = document.querySelector('.item-name-select');
    if (firstSelect) {
        firstSelect.addEventListener('change', function() { updateUnit(this); });
    }
    // Add event listener for adding new items
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'item-row border rounded p-3 mb-3';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Item Name</label>
                    <select class="form-select item-name-select" name="items[${itemCounter}][name]" required>
                        <option value="">Select Commodity</option>
                        @foreach($commodities as $commodity)
                            <option value="{{ $commodity->name }}" data-metric="{{ $commodity->metric }}">{{ $commodity->name }} ({{ $commodity->quantity }} {{ $commodity->metric }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="items[${itemCounter}][quantity]" min="1" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text" class="form-control unit-input" name="items[${itemCounter}][unit]" value="" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Description/Specifications</label>
                    <textarea class="form-control" name="items[${itemCounter}][description]" rows="2" placeholder="Additional details about the item"></textarea>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        // Add event listener for the new select
        const newSelect = newRow.querySelector('.item-name-select');
        if (newSelect) {
            newSelect.addEventListener('change', function() { updateUnit(this); });
        }
        itemCounter++;
    });
    // Initial unit update for first row
    if (firstSelect) {
        firstSelect.addEventListener('change', function() { updateUnit(this); });
    }
});
</script>
@endpush