@extends('layouts.app')

@section('title', 'Machineries')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="fw-bold mb-0">Machineries</h1>
                            <p class="text-muted">Estate machinery management system</p>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Add Machinery Button -->
                            <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#machineryModal" 
                                onclick="openMachineryModal(null)">
                                <i class="bi bi-plus-circle me-2"></i> Add Machinery
                            </button>
                        </div>
                    </div>

                    <!-- Dashboard Cards for Statistics -->
                    @php
                        $totalMachineryRecords = 0;
                        $totalUniqueItems = 0;

                        if (isset($machineries) && !$machineries->isEmpty()) {
                            $totalMachineryRecords = $machineries->count();
                            $totalUniqueItems = $machineries->unique('name')->count();
                        }
                    @endphp

                    <div class="row row-cols-1 row-cols-md-2 g-4 mt-4">
                        <!-- Total Items (Unique) -->
                        <div class="col">
                            <div class="card shadow-sm border-0 dashboard-card" style="background-color: #17a2b8;">
                                <div class="card-body text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-0">{{ $totalUniqueItems }}</h3>
                                            <p class="mb-0">Total Items</p>
                                        </div>
                                        <div class="ms-3">
                                            <i class="bi bi-gear-wide-connected" style="font-size: 2rem; opacity: 0.2;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Quantity (All Records) -->
                        <div class="col">
                            <div class="card shadow-sm border-0 dashboard-card" style="background-color: #6f42c1;">
                                <div class="card-body text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h3 class="mb-0">{{ $totalMachineryRecords }}</h3>
                                            <p class="mb-0">Total Quantity</p>
                                        </div>
                                        <div class="ms-3">
                                            <i class="bi bi-stack" style="font-size: 2rem; opacity: 0.2;"></i>
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

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-lg">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (!isset($machineries) || $machineries->isEmpty())
        <div class="alert alert-warning text-center">No machineries found.</div>
    @else
        @php
            // Group machineries by name for card display
            $groupedMachineries = $machineries->groupBy('name');
        @endphp
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($groupedMachineries as $name => $items)
                @php
                    $item = $items->first();
                    $multiple = $items->count() > 1;
                @endphp
                
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow equipment-card" 
                         data-equipment-name="{{ $name }}"
                         data-model="{{ $item->model }}"
                         data-reg-num="{{ $item->reg_num ?? '' }}"
                         data-group-data="{{ json_encode($items) }}"
                         style="cursor: pointer;">
                        <div class="bg-dark px-3 py-3">
                            <h5 class="text-white mb-0">{{ $name }}</h5>
                        </div>
                        
                        <div class="card-body">
                            <div class="text-muted small fw-bold">Model: {{ $item->model }}</div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                            <small class="text-muted">Machinery ID: {{ $item->id }}</small>
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

<!-- Modal for Adding/Editing Machinery -->
<div class="modal fade" id="machineryModal" tabindex="-1" aria-labelledby="machineryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="machineryModalLabel">Add Machinery</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="form-errors" class="alert alert-danger" style="display: none;">
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2"></ul>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any() && !$errors->has('success'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="machineryForm" action="{{ route('machinery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">
                    <input type="hidden" name="id" id="machineryId">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                <div class="invalid-feedback"></div>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" name="model" id="model" class="form-control" value="{{ old('model') }}" required>
                                <div class="invalid-feedback"></div>
                                @error('model')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="reg_num" class="form-label">Registration Number (Optional)</label>
                                <input type="text" name="reg_num" id="reg_num" class="form-control" value="{{ old('reg_num') }}">
                                <div class="invalid-feedback"></div>
                                @error('reg_num')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="OPERATIONAL" {{ old('status') == 'OPERATIONAL' ? 'selected' : '' }}>OPERATIONAL</option>
                                    <option value="UNDER MAINTENANCE" {{ old('status') == 'UNDER MAINTENANCE' ? 'selected' : '' }}>UNDER MAINTENANCE</option>
                                    <option value="OUT OF SERVICE" {{ old('status') == 'OUT OF SERVICE' ? 'selected' : '' }}>OUT OF SERVICE</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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

<!-- Modal for Machinery Details -->
<div class="modal fade" id="machineryDetailsModal" tabindex="-1" aria-labelledby="machineryDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white border-bottom-0">
                <h5 class="modal-title fw-bold" id="machineryDetailsModalLabel">Machinery Details</h5>
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
                                    <table class="table table-hover mb-0" id="machineryItemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Model</th>
                                                <th>Reg. Number</th>
                                                <th>Status</th>
                                                <th class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="machineryItemsTableBody">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to machinery cards
        const machineryCards = document.querySelectorAll('.equipment-card');
        machineryCards.forEach(card => {
            card.addEventListener('click', function(event) {
                // Prevent modal from opening if a button or interactive element inside the card was clicked
                if (event.target.closest('button, .dropdown, a, input, select, textarea')) {
                    event.stopPropagation();
                    return;
                }
                // Pass the card element directly to the function
                viewMachineryDetails(this);
            });
        });

        const machineryModal = document.getElementById('machineryModal');
        if (machineryModal) {
            machineryModal.addEventListener('hidden.bs.modal', function () {
                // Remove any lingering modal backdrops
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';
            });
        }
    });

    function openMachineryModal(machinery) {
        const form = document.getElementById('machineryForm');
        const modalTitle = document.getElementById('machineryModalLabel');
        const submitButton = document.getElementById('modalSubmitButton');
        const methodField = document.getElementById('methodField');
        const machineryIdField = document.getElementById('machineryId');
        const nameField = document.getElementById('name');
        const modelField = document.getElementById('model');
        const regNumField = document.getElementById('reg_num');
        const statusField = document.getElementById('status');

        if (machinery) {
            // Edit mode
            modalTitle.textContent = 'Edit Machinery';
            submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Update';
            form.action = `/machinery/${machinery.id}`;
            methodField.value = 'PUT';
            machineryIdField.value = machinery.id;

            nameField.value = machinery.name || '';
            modelField.value = machinery.model || '';
            regNumField.value = machinery.reg_num || '';
            statusField.value = machinery.status ? machinery.status.toUpperCase() : 'OPERATIONAL';
        } else {
            // Add mode
            modalTitle.textContent = 'Add Machinery';
            submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Add';
            form.action = '{{ route("machinery.store") }}';
            methodField.value = 'POST';
            machineryIdField.value = '';

            nameField.value = '';
            modelField.value = '';
            regNumField.value = '';
            statusField.value = 'OPERATIONAL';
        }

        const modal = new bootstrap.Modal(document.getElementById('machineryModal'));
        modal.show();
    }

    function viewMachineryDetails(cardElement) {
        try {
            const equipmentName = cardElement.getAttribute('data-equipment-name');
            const allItems = JSON.parse(cardElement.getAttribute('data-group-data'));

            if (!allItems || allItems.length === 0) {
                console.error('Machinery data not found or empty for:', equipmentName);
                alert('Machinery details not found.');
                return;
            }

            const modalTitle = document.getElementById('machineryDetailsModalLabel');
            const itemsTableBody = document.getElementById('machineryItemsTableBody');
            if (!modalTitle || !itemsTableBody) {
                console.error('Modal elements not found');
                return;
            }

            modalTitle.textContent = `Details for ${equipmentName}`;
            itemsTableBody.innerHTML = ''; // Clear previous content

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

            allItems.forEach(item => {
                const statusUpper = item.status ? item.status.toUpperCase() : 'UNKNOWN';
                let statusClass = 'bg-secondary';
                if (statusUpper === 'OPERATIONAL') statusClass = 'bg-success';
                else if (statusUpper === 'UNDER MAINTENANCE') statusClass = 'bg-warning text-dark';
                else if (statusUpper === 'OUT OF SERVICE') statusClass = 'bg-danger';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.model || 'N/A'}</td>
                    <td>${item.reg_num || 'N/A'}</td>
                    <td><span class="badge ${statusClass}">${statusUpper}</span></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-warning edit-item-btn me-1" data-item-id="${item.id}" style="z-index: 10;">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <form action="/machinery/${item.id}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger" style="z-index: 10;">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    </td>
                `;
                itemsTableBody.appendChild(row);
            });

            // Attach event listeners for edit buttons
            attachEditButtonListeners(allItems);

            const detailsModal = new bootstrap.Modal(document.getElementById('machineryDetailsModal'));
            detailsModal.show();
        } catch (error) {
            console.error('Error in viewMachineryDetails:', error);
            alert('An error occurred while showing machinery details.');
        }
    }

    function attachEditButtonListeners(allItems) {
        const editButtons = document.querySelectorAll('.edit-item-btn');
        editButtons.forEach(btn => {
            // Remove existing listeners to prevent duplicates
            btn.removeEventListener('click', handleEditClick);
            btn.addEventListener('click', handleEditClick);

            function handleEditClick(event) {
                event.stopPropagation();
                const itemId = btn.getAttribute('data-item-id');
                const itemToEdit = allItems.find(item => item.id == itemId);
                
                if (itemToEdit) {
                    const detailsModal = bootstrap.Modal.getInstance(document.getElementById('machineryDetailsModal'));
                    if (detailsModal) {
                        detailsModal.hide();
                        setTimeout(() => {
                            openMachineryModal(itemToEdit);
                        }, 500);
                    } else {
                        openMachineryModal(itemToEdit);
                    }
                } else {
                    console.error('Item not found for ID:', itemId);
                    alert('Item not found.');
                }
            }
        });
    }
</script>

<style>
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
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
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
        position: relative;
        z-index: 10;
    }
    
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1.25rem 1.5rem;
    }
    
    .alert {
        border-radius: 8px;
    }
    
    .card .bg-dark {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .form-control, .form-select {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
    }
    
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
    
    #machineryDetailsModal .modal-dialog {
        max-width: 1200px;
    }

    #machineryDetailsModal .modal-content {
        border-radius: 16px;
    }

    #machineryDetailsModal .modal-body {
        background-color: #f8f9fa;
    }

    #machineryDetailsModal .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }

    #machineryItemsTable {
        font-size: 0.95rem;
    }

    #machineryItemsTable th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 1rem;
    }

    #machineryItemsTable td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }

    #machineryItemsTable .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }

    #machineryItemsTable .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }

    #machineryDetailsModal .card {
        border-radius: 12px;
        overflow: hidden;
    }

    #machineryDetailsModal .card-header {
        border-bottom: none;
    }

    #machineryDetailsModal .btn {
        padding: 0.5rem 1.5rem;
        font-size: 0.95rem;
        border-radius: 8px;
    }
    
    .equipment-card {
        cursor: pointer;
        position: relative;
    }
    
    .equipment-card button,
    .equipment-card .dropdown {
        position: relative;
        z-index: 10;
    }
    
    .card-footer .text-primary {
        font-weight: bold;
    }

    #machineryItemsTable img.img-thumbnail {
        border-radius: 4px;
        vertical-align: middle;
    }

    #machineryItemsTable tbody tr {
        transition: background-color 0.2s ease;
    }

    #machineryItemsTable tbody tr:hover {
        background-color: #e9ecef;
        cursor: default;
    }

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
</style>

@endsection

@push('scripts')
<!-- Removed AJAX form submission for machinery. The form will now submit normally. -->
@endpush