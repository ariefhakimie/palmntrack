@extends('layouts.app')

@section('title', 'Equipment')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="fw-bold mb-0">Equipment</h1>
                            <p class="text-muted">Estate equipment management system</p>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Add Equipment Button -->
                            <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#equipmentModal" 
                                onclick="openEquipmentModal(null)">
                                <i class="bi bi-plus-circle me-2"></i> Add Equipment
                            </button>
                        </div>
                    </div>

                    <!-- Dashboard Cards for Statistics -->
                    @php
                        $totalUniqueItems = 0;
                        $totalQuantity = 0;
                        if (isset($equipment) && !$equipment->isEmpty()) {
                            $totalUniqueItems = $equipment->unique('name')->count();
                            $totalQuantity = $equipment->count();
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
                                            <i class="bi bi-tools" style="font-size: 2rem; opacity: 0.2;"></i>
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

    @if ($equipment->isEmpty())
        <div class="alert alert-warning text-center">No equipment found.</div>
    @else
        @php
            // Group equipment by name for card display
            $groupedEquipment = $equipment->groupBy('name');
        @endphp
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($groupedEquipment as $name => $items)
                @php
                    $item = $items->first();
                    $multiple = $items->count() > 1;
                @endphp
                
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow equipment-card" 
                         data-equipment-name="{{ $name }}"
                         data-model="{{ $item->model }}"
                         data-group-data="{{ json_encode($items) }}"
                         style="cursor: pointer;">
                        <div class="bg-dark px-3 py-3">
                            <h5 class="text-white mb-0">{{ $name }}</h5>
                        </div>
                        
                        <div class="card-body">
                            <div class="text-muted small fw-bold">Model: {{ $item->model }}</div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                            <small class="text-muted">Equipment ID: {{ $item->id }}</small>
                            @if($multiple)
                                <small class="text-primary">Multiple items →</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        

    @endif

    <!-- Modal for Adding/Editing Equipment -->
    <div class="modal fade" id="equipmentModal" tabindex="-1" aria-labelledby="equipmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="equipmentModalLabel">Add Equipment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="equipmentForm" action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="methodField" value="POST">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" name="model" id="model" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="OPERATIONAL">OPERATIONAL</option>
                                        <option value="UNDER MAINTENANCE">UNDER MAINTENANCE</option>
                                        <option value="OUT OF SERVICE">OUT OF SERVICE</option>
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

    <!-- Modal for Equipment Details -->
    <div class="modal fade" id="equipmentDetailsModal" tabindex="-1" aria-labelledby="equipmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white border-bottom-0">
                    <h5 class="modal-title fw-bold" id="equipmentDetailsModalLabel">Equipment Details</h5>
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
                                                    <th>ID</th>
                                                    <th>Model</th>
                                                    <th>Status</th>
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
        // Add click event listeners to equipment cards
        const equipmentCards = document.querySelectorAll('.equipment-card');
        equipmentCards.forEach(card => {
            card.addEventListener('click', function(event) {
                // Prevent modal from opening if a button or interactive element inside the card was clicked
                if (event.target.closest('button, .dropdown, a, input, select, textarea')) {
                    event.stopPropagation();
                    return;
                }
                // Pass the card element directly to the function
                viewEquipmentDetails(this);
            });
        });

        function openEquipmentModal(equipment) {
            const form = document.getElementById('equipmentForm');
            const modalTitle = document.getElementById('equipmentModalLabel');
            const submitButton = document.getElementById('modalSubmitButton');
            const methodField = document.getElementById('methodField');

            if (equipment) {
                // Edit mode
                modalTitle.textContent = 'Edit Equipment';
                submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Update';
                form.action = `/equipment/${equipment.id}`;
                methodField.value = 'PUT';

                document.getElementById('name').value = equipment.name || '';
                document.getElementById('model').value = equipment.model || '';
                document.getElementById('status').value = equipment.status ? equipment.status.toUpperCase() : 'OPERATIONAL';
            } else {
                // Add mode
                modalTitle.textContent = 'Add Equipment';
                submitButton.innerHTML = '<i class="bi bi-check-circle me-1"></i> Add';
                form.action = '{{ route("equipment.store") }}';
                methodField.value = 'POST';

                document.getElementById('name').value = '';
                document.getElementById('model').value = '';
                document.getElementById('status').value = 'OPERATIONAL';
            }

            // Ensure the modal is shown
            const equipmentModal = new bootstrap.Modal(document.getElementById('equipmentModal'));
            equipmentModal.show();
        }

        function viewEquipmentDetails(cardElement) {
            try {
                const equipmentName = cardElement.getAttribute('data-equipment-name');
                const allItems = JSON.parse(cardElement.getAttribute('data-group-data'));
                
                if (!allItems || allItems.length === 0) {
                    console.error('Equipment data not found or empty for:', equipmentName);
                    alert('Equipment details not found.');
                    return;
                }

                const modalTitle = document.getElementById('equipmentDetailsModalLabel');
                const itemsTableBody = document.getElementById('itemsTableBody');
                if (!itemsTableBody || !modalTitle) {
                    console.error('Modal elements not found');
                    return;
                }
                
                modalTitle.textContent = `Details for ${equipmentName}`;
                itemsTableBody.innerHTML = '';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

                allItems.forEach(item => {
                    const statusUpper = item.status ? item.status.toUpperCase() : 'UNKNOWN';
                    const statusClass = statusUpper === 'OPERATIONAL' ? 'bg-success' : 
                                      (statusUpper === 'UNDER MAINTENANCE' ? 'bg-warning text-dark' : 'bg-danger');
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.model || 'N/A'}</td>
                        <td><span class="badge ${statusClass}">${statusUpper}</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-warning edit-item-btn me-1" data-item-id="${item.id}" style="z-index: 10;">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <form action="/equipment/${item.id}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
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

                const detailsModal = new bootstrap.Modal(document.getElementById('equipmentDetailsModal'));
                detailsModal.show();
            } catch (error) {
                console.error('Error in viewEquipmentDetails:', error);
                alert('An error occurred while showing equipment details.');
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
                        const detailsModal = bootstrap.Modal.getInstance(document.getElementById('equipmentDetailsModal'));
                        if (detailsModal) {
                            detailsModal.hide();
                            setTimeout(() => {
                                openEquipmentModal(itemToEdit);
                            }, 500);
                        } else {
                            openEquipmentModal(itemToEdit);
                        }
                    } else {
                        console.error('Item not found for ID:', itemId);
                        alert('Item not found.');
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const equipmentModal = document.getElementById('equipmentModal');
            if (equipmentModal) {
                equipmentModal.addEventListener('hidden.bs.modal', function () {
                    // Remove any lingering modal backdrops
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style = '';
                });
            }
        });
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
            position: relative;
            z-index: 10;
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
        :: mince-linkchecker-ignore
-webkit-scrollbar {
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
        
        /* Enhanced styling for Equipment Details modal */
        #equipmentDetailsModal .modal-dialog {
            max-width: 1200px;
        }

        #equipmentDetailsModal .modal-content {
            border-radius: 16px;
        }

        #equipmentDetailsModal .modal-body {
            background-color: #f8f9fa;
        }

        #equipmentDetailsModal .modal-header {
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

        #equipmentDetailsModal .card {
            border-radius: 12px;
            overflow: hidden;
        }

        #equipmentDetailsModal .card-header {
            border-bottom: none;
        }

        #equipmentDetailsModal .btn {
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
            z-index: 10;
        }
        
        /* Badge for showing multiple items indicator */
        .card-footer .text-primary {
            font-weight: bold;
        }

        /* Image styling in table */
        #itemsTable img.img-thumbnail {
            border-radius: 4px;
            vertical-align: middle;
        }

        /* Hover effect for table rows */
        #itemsTable tbody tr {
            transition: background-color 0.2s ease;
        }

        #itemsTable tbody tr:hover {
            background-color: #e9ecef;
            cursor: default;
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

        /* Spinner for loading state */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</div>
@endsection