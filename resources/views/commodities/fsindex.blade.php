@extends('layouts.app')

@section('title', 'Commodities')

@section('content')
<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="fw-bold mb-0">Commodities</h1>
                            <p class="text-muted">Estate inventory management system</p>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Add Commodity Button -->
                            <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#commodityModal" 
                                onclick="openCommodityModal(null)">
                                <i class="bi bi-plus-circle me-2"></i> Stock In
                            </button>
                            <!-- Stock Out Button -->
                            <button class="btn btn-danger px-3" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                                <i class="bi bi-box-arrow-right me-2"></i> Stock Out
                            </button>
                            <!-- Order Stock Button -->
                            <a href="{{ route('commodities.orderstock') }}" class="btn btn-success px-3">
                                Order Stock
                            </a>
                        </div>
                    </div>

                    <!-- Dashboard Cards and Data Processing -->
                    @php
                        // Group commodities and calculate stats
                        $groupedCommodities = [];
                        $totalQuantity = 0;

                        if (isset($commodities) && !$commodities->isEmpty()) {
                            foreach($commodities as $item) {
                                // Sum total quantity of all individual items
                                $totalQuantity += $item->quantity;

                                // Group items by name
                                if (!isset($groupedCommodities[$item->name])) {
                                    $groupedCommodities[$item->name] = [
                                        'item' => $item,
                                        'total_quantity' => 0,
                                        'all_items' => [],
                                        'metrics' => []
                                    ];
                                }
                                $groupedCommodities[$item->name]['total_quantity'] += $item->quantity;
                                $groupedCommodities[$item->name]['all_items'][] = $item;
                                
                                if (isset($groupedCommodities[$item->name]['metrics'][$item->metric])) {
                                    $groupedCommodities[$item->name]['metrics'][$item->metric] += $item->quantity;
                                } else {
                                    $groupedCommodities[$item->name]['metrics'][$item->metric] = $item->quantity;
                                }
                            }
                        }
                        // The number of total items is the count of the groups
                        $totalItems = count($groupedCommodities);
                    @endphp

                    <div class="row row-cols-1 row-cols-md-2 g-4 mt-4">
                        <!-- Total Items -->
                        <div class="col">
                            <div class="card shadow-sm border-0 dashboard-card" style="background-color: #17a2b8;">
                                <div class="card-body text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-0">{{ $totalItems }}</h3>
                                            <p class="mb-0">Total Items</p>
                                        </div>
                                        <div class="ms-3">
                                            <i class="bi bi-boxes" style="font-size: 2rem; opacity: 0.2;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Quantity -->
                        <div class="col">
                            <div class="card shadow-sm border-0 dashboard-card" style="background-color: #6610f2;">
                                <div class="card-body text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-0">{{ $totalQuantity }}</h3>
                                            <p class="mb-0">Total Quantity</p>
                                        </div>
                                        <div class="ms-3">
                                            <i class="bi bi-calculator" style="font-size: 2rem; opacity: 0.2;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0 rounded-lg">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

    @if (!isset($commodities) || $commodities->isEmpty())
        <div class="alert alert-warning text-center">No commodities found.</div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($groupedCommodities as $name => $groupData)
                @php
                    $item = $groupData['item'];
                    $totalQuantity = $groupData['total_quantity'];
                    $allItems = $groupData['all_items'];
                    $multiple = count($allItems) > 1;
                    $metrics = $groupData['metrics'];
                    // Format metrics display
                    $metricsDisplay = collect($metrics)->map(function($quantity, $metric) {
                        return "$quantity $metric";
                    })->implode(', ');
                @endphp
                
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow equipment-card" 
                         data-equipment-name="{{ $name }}"
                         data-type="{{ $item->type ?? 'N/A' }}"
                         data-group-data="{{ json_encode($allItems) }}"
                         style="cursor: pointer;">
                        <div class="position-absolute end-0 top-0 m-2 d-flex flex-column align-items-end">
                            @if($multiple)
                                <span class="badge bg-info rounded-pill mt-1" title="Multiple items">{{ count($allItems) }}</span>
                            @endif
                        </div>
                        
                        <div class="bg-dark px-3 py-3">
                            <h5 class="text-white mb-0">{{ $name }}</h5>
                        </div>
                        
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-primary text-white fw-bold" style="font-size: 1em; letter-spacing: 0.5px;">{{ $item->type ?? 'General' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fs-5 fw-bold">
                                    {{ $metricsDisplay }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                            <small class="text-muted">Commodity ID: {{ $item->id }}</small>
                            @if($multiple)
                                <small class="text-primary">Multiple items →</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        

    @endif
</div>

<!-- Modal for Adding/Editing Commodity -->
<div class="modal fade" id="commodityModal" tabindex="-1" aria-labelledby="commodityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="commodityModalLabel">Add Commodity</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="commodityForm" method="POST" action="{{ route('commodities.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="Fertilizer">Fertilizer</option>
                                    <option value="Pesticide">Pesticide</option>
                                    <option value="Seed">Seed</option>
                                    <option value="Feed">Feed</option>
                                    <option value="Fuel">Fuel</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="supplier" class="form-label">Supplier</label>
                                <select name="supplier" id="supplier" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @if(isset($suppliers))
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier }}">{{ $supplier }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="metric" class="form-label">Metric</label>
                                <select name="metric" id="metric" class="form-control" required>
                                    <option value="">Select Metric</option>
                                    <option value="pcs">Pieces</option>
                                    <option value="kg">Kilograms</option>
                                    <option value="l">Liters</option>
                                    <option value="m">Meters</option>
                                    <option value="boxes">Boxes</option>
                                    <option value="bags">Bags</option>
                                    <option value="rolls">Rolls</option>
                                    <option value="units">Units</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="modalSubmitButton">
                            <i class="bi bi-check-circle me-1"></i> Add
                        </button>
                    </div>
                </form>
            </div>  
        </div>
    </div>
</div>

<!-- Modal for Stock Out -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-left: 8px solid #dc3545;">
            <div class="modal-header bg-danger text-white align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <span class="display-5"><i class="fas fa-arrow-circle-down"></i></span>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="stockOutModalLabel">Stock Out</h5>
                        <small class="text-white-50">Remove items from your inventory</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <form id="stockOutForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="stockout_commodity_id" class="form-label fw-semibold">Commodity</label>
                                        <select name="commodity_id" id="stockout_commodity_id" class="form-select" required>
                                            <option value="">Select Commodity</option>
                                            @foreach($commodities as $commodity)
                                                <option value="{{ $commodity->id }}" data-current-stock="{{ $commodity->quantity }}">
                                                    {{ $commodity->name }} (Available: {{ $commodity->quantity }} {{ $commodity->metric }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="stockout_quantity" class="form-label fw-semibold">Quantity to Remove</label>
                                        <input type="number" name="quantity" id="stockout_quantity" class="form-control" required min="1" placeholder="Enter quantity to remove">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-warning d-flex align-items-center gap-2 shadow-sm">
                                <span class="fs-4"><i class="fas fa-exclamation-triangle"></i></span>
                                <span id="stockOutInfo" class="fw-semibold">Please select a commodity to see available stock levels.</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger px-4 fw-bold shadow">
                            <i class="fas fa-arrow-right me-2"></i> Confirm Stock Out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Commodity Details -->
<div class="modal fade" id="commodityDetailsModal" tabindex="-1" aria-labelledby="commodityDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white border-bottom-0">
                <h5 class="modal-title fw-bold" id="commodityDetailsModalLabel">Commodity Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-secondary text-white py-3">
                                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Individual Items</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="itemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Metric</th>
                                                <th>Supplier</th>
                                                <th class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsTableBody">
                                            <!-- Items will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Prepare a JS object mapping item names to their details
    const commodityData = @json($commodities->keyBy('name')->map(function($item) {
        return [
            'metric' => $item->metric,
            'type' => $item->type,
            'supplier' => $item->supplier
        ];
    }));

    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to commodity cards
        const equipmentCards = document.querySelectorAll('.equipment-card');
        equipmentCards.forEach(card => {
            card.addEventListener('click', function(event) {
                // Prevent the click if the target is a button, dropdown, or link
                if (event.target.closest('button, a, .dropdown')) {
                    return;
                }
                // Pass the card element directly
                viewCommodityDetails(this);
            });
        });

        // Other listeners...
        const stockOutSelect = document.getElementById('stockout_commodity_id');
        if (stockOutSelect) {
            stockOutSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const infoEl = document.getElementById('stockOutInfo');
                const qtyInput = document.getElementById('stockout_quantity');
                
                if (selectedOption.value && infoEl && qtyInput) {
                    const commodityName = selectedOption.text.split(' (Available:')[0];
                    const currentStock = selectedOption.dataset.currentStock;
                    infoEl.textContent = `Removing ${commodityName} from inventory. Available stock: ${currentStock}`;
                    qtyInput.max = currentStock;
                } else if(infoEl) {
                    infoEl.textContent = 'Please select a commodity to see available stock levels.';
                }
            });
        }
    });

    function openCommodityModal(commodity) {
        const form = document.getElementById('commodityForm');
        const modal = new bootstrap.Modal(document.getElementById('commodityModal'));
        const modalTitle = document.getElementById('commodityModalLabel');
        const submitButton = document.getElementById('modalSubmitButton');
        const methodField = document.getElementById('methodField');

        // Always reset the form for a clean state
        form.reset();

        // Remove previous validation errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        // Remove id field if it exists from a previous edit
        const existingIdField = form.querySelector('input[name="id"]');
        if (existingIdField) {
            existingIdField.remove();
        }

        if (commodity) {
            // ----- EDIT MODE -----
            modalTitle.textContent = 'Edit Commodity';
            submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Update';
            form.action = `/commodities/${commodity.id}`;
            methodField.value = 'PUT';

            // Create and prepend hidden ID field
            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'id';
            idField.value = commodity.id;
            form.prepend(idField);

            // Populate form fields
            document.getElementById('name').value = commodity.name;
            document.getElementById('quantity').value = commodity.quantity;
            document.getElementById('metric').value = commodity.metric;
            document.getElementById('type').value = commodity.type;

        } else {
            // ----- ADD MODE -----
            modalTitle.textContent = 'Stock In';
            submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Add';
            form.action = '{{ route("commodities.store") }}';
            methodField.value = 'POST';
        }
    }
    
    function quickOrder(commodityId, commodityName) {
        // Open the order modal
        const orderModal = new bootstrap.Modal(document.getElementById('orderStockModal'));
        orderModal.show();
        
        // Set the selected commodity
        document.getElementById('commodity_id').value = commodityId;
        
        // Update the stock info
        const selectedOption = document.querySelector(`#commodity_id option[value="${commodityId}"]`);
        const currentStock = selectedOption ? selectedOption.dataset.currentStock : 'N/A';
        document.getElementById('stockInfo').textContent = `Ordering ${commodityName}. Current stock: ${currentStock}`;
    }
    
    function quickStockOut(commodityId, commodityName) {
        // Open the stock out modal
        const stockOutModal = new bootstrap.Modal(document.getElementById('stockOutModal'));
        stockOutModal.show();
        
        // Set the selected commodity
        document.getElementById('stockout_commodity_id').value = commodityId;
        
        // Update the stock info
        const selectedOption = document.querySelector(`#stockout_commodity_id option[value="${commodityId}"]`);
        const currentStock = selectedOption ? selectedOption.dataset.currentStock : 'N/A';
        document.getElementById('stockOutInfo').textContent = `Removing ${commodityName} from inventory. Available stock: ${currentStock}`;
    }
    
    // Update stock info when commodity selection changes in Order Stock modal
    document.getElementById('commodity_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const commodityName = selectedOption.text.split(' (Current:')[0];
            const currentStock = selectedOption.dataset.currentStock;
            document.getElementById('stockInfo').textContent = `Ordering ${commodityName}. Current stock: ${currentStock}`;
        } else {
            document.getElementById('stockInfo').textContent = 'Please select a commodity to see current stock levels.';
        }
    });
    
    // Update stock info when commodity selection changes in Stock Out modal
    document.getElementById('stockout_commodity_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const commodityName = selectedOption.text.split(' (Available:')[0];
            const currentStock = selectedOption.dataset.currentStock;
            document.getElementById('stockOutInfo').textContent = `Removing ${commodityName} from inventory. Available stock: ${currentStock}`;
            
            // Set max quantity to available stock
            document.getElementById('stockout_quantity').max = currentStock;
        } else {
            document.getElementById('stockOutInfo').textContent = 'Please select a commodity to see available stock levels.';
        }
    });
    
    // Validate that stock out quantity doesn't exceed available stock
    document.getElementById('stockout_quantity').addEventListener('change', function() {
        const commoditySelect = document.getElementById('stockout_commodity_id');
        const selectedOption = commoditySelect.options[commoditySelect.selectedIndex];
        
        if (selectedOption.value) {
            const currentStock = parseInt(selectedOption.dataset.currentStock);
            const requestedQuantity = parseInt(this.value);
            
            if (requestedQuantity > currentStock) {
                alert('Warning: The quantity you entered exceeds the available stock.');
                this.value = currentStock;
            }
        }
    });

    // Function to view commodity details in modal for grouped commodities
    function viewCommodityDetails(cardElement) {
        try {
            const allItems = JSON.parse(cardElement.getAttribute('data-group-data'));
            const equipmentName = cardElement.getAttribute('data-equipment-name');
            
            if (!allItems || allItems.length === 0) {
                console.error('Commodity data not found or empty for:', equipmentName);
                alert('Commodity details not found');
                return;
            }
            
            // Populate items table
            const itemsTableBody = document.getElementById('itemsTableBody');
            if (!itemsTableBody) {
                console.error('Items table body not found');
                return;
            }
            itemsTableBody.innerHTML = ''; // Clear existing rows

            const csrfToken = '{{ csrf_token() }}';
            
            allItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="ps-4">${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.type || 'N/A'}</td>
                    <td>${item.quantity}</td>
                    <td>${item.metric}</td>
                    <td>${item.supplier || 'N/A'}</td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-warning edit-item-btn me-1" data-item-id="${item.id}">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <form action="/commodities/${item.id}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this commodity?');">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash me-1"></i> Delete
                        </button>
                        </form>
                    </td>
                `;
                itemsTableBody.appendChild(row);
            });
            
            // Add event listeners to the edit buttons in the table
            document.querySelectorAll('.edit-item-btn').forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const itemId = this.getAttribute('data-item-id');
                    const itemToEdit = allItems.find(item => item.id == itemId);
                    
                    if (itemToEdit) {
                        // Close details modal
                        const detailsModal = bootstrap.Modal.getInstance(document.getElementById('commodityDetailsModal'));
                        if (detailsModal) detailsModal.hide();
                        
                        // Open edit modal with commodity data
                        setTimeout(() => {
                            openCommodityModal(itemToEdit);
                            const editModal = new bootstrap.Modal(document.getElementById('commodityModal'));
                            editModal.show();
                        }, 500);
                    }
                });
            });
            
            // Show the modal
            const detailsModalElement = document.getElementById('commodityDetailsModal');
            if (!detailsModalElement) {
                console.error('Details modal element not found');
                return;
            }
            const detailsModal = new bootstrap.Modal(detailsModalElement);
            detailsModal.show();
        } catch (error) {
            console.error('Error in viewCommodityDetails:', error);
            alert('An error occurred while loading commodity details. Please check the console for details.');
        }
    }
</script>

<style>
    /* Card styling */
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Badge styling */
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
    /* Custom colors for status indicators */
    .bg-success {
        background-color: #28a745 !important;
    }
    
    .bg-warning {
        background-color: #ffc107 !important;
    }
    
    .bg-danger {
        background-color: #dc3545 !important;
    }
    
    .bg-info {
        background-color: #17a2b8 !important;
    }
    
    /* Button styling */
    .btn {
        border-radius: 6px;
        font-weight: 500;
    }
    
    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1.25rem 1.5rem;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 8px;
    }
    
    /* Card overlay gradient */
    .card .bg-dark {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    /* Make sure inputs are consistent */
    .form-control, .form-select {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Enhanced styling for Commodity Details modal */
    #commodityDetailsModal .modal-dialog {
        max-width: 1200px;
    }

    #commodityDetailsModal .modal-content {
        border-radius: 16px;
    }

    #commodityDetailsModal .modal-body {
        background-color: #f8f9fa;
    }

    #commodityDetailsModal .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }

    #itemsTable {
        font-size: 0.95rem;
    }

    #itemsTable th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 1rem;
    }

    #itemsTable td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }

    #itemsTable .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }

    #itemsTable .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }

    #commodityDetailsModal .card {
        border-radius: 12px;
        overflow: hidden;
    }

    #commodityDetailsModal .card-header {
        border-bottom: none;
    }

    #commodityDetailsModal .btn {
        padding: 0.5rem 1.5rem;
        font-size: 0.95rem;
        border-radius: 8px;
    }
    
    /* Adding cursor pointer for clickable cards */
    .equipment-card {
        cursor: pointer;
        position: relative;
    }
    
    /* Fix for clickable card with buttons inside */
    .equipment-card button,
    .equipment-card .dropdown {
        position: relative;
        z-index: 2;
    }
    
    /* Badge for showing multiple items indicator */
    .card-footer .text-primary {
        font-weight: bold;
    }

    /* Hover effect for table rows */
    #itemsTable tbody tr {
        transition: background-color 0.2s ease;
    }

    #itemsTable tbody tr:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }

    /* Dashboard Card Styling */
    .dashboard-card {
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        transition: transform 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .dashboard-card .card-body {
        padding: 1.5rem;
    }

    .dashboard-card h3 {
        font-size: 2rem;
        font-weight: bold;
    }

    .dashboard-card p {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .dashboard-card a {
        font-size: 0.8rem;
        font-weight: 500;
        opacity: 0.8;
    }

    .dashboard-card a:hover {
        opacity: 1;
    }

    #stockOutModal .modal-content {
        border-radius: 1rem;
    }
    #stockOutModal .modal-header {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    #stockOutModal .modal-footer {
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }
    #stockOutModal .form-label {
        font-size: 1rem;
    }
    #stockOutModal .form-control, #stockOutModal .form-select {
        min-height: 48px;
        font-size: 1.1rem;
    }
    #stockOutModal .alert-warning {
        background: #fff3cd;
        border-left: 5px solid #dc3545;
        font-size: 1.05rem;
    }
</style>

@endsection