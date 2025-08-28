@extends('layouts.app')

@section('title', 'Usage Records')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="fw-bold mb-0">Usage Records</h1>
                            <p class="text-muted">Estate machinery and equipment usage tracking system</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addUsageRecordModal">
                                <i class="bi bi-plus-circle me-2"></i> Add Usage Record
                            </button>
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

    <!-- Empty State -->
    @if($records->isEmpty())
        <div class="alert alert-warning text-center shadow-sm border-0 rounded-lg">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>No usage records found.
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="bg-dark px-3 py-3">
                        <h5 class="text-white mb-0">Sample Record</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-info rounded-pill">Sample</span>
                            <div class="fs-5 fw-bold text-success">Ali</div>
                        </div>
                        <p class="card-text small text-muted mb-3">
                            <i class="bi bi-gear me-2"></i>Item: Tractor X<br>
                            <i class="bi bi-calendar3 me-2"></i>Usage Date: 2025-04-29
                        </p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning flex-grow-1" disabled><i class="bi bi-pencil"></i> Edit</button>
                            <button class="btn btn-sm btn-danger" disabled><i class="bi bi-trash"></i> Delete</button>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <small class="text-muted">ID: 001</small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Card View for Records -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($records as $record)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow record-card" 
                         data-record-id="{{ $record->id }}"
                         data-user-id="{{ optional($record->user)->id ?? '' }}"
                         data-machinery-id="{{ optional($record->machinery)->id ?? '' }}"
                         data-equipment-id="{{ optional($record->equipment)->id ?? '' }}"
                         data-item-type="{{ $record->machinery_id ? 'machinery' : 'equipment' }}"
                         data-date="{{ $record->usage_timestamps }}"
                         style="cursor: pointer;">
                        <div class="position-absolute end-0 top-0 m-2">
                            <span class="badge bg-info rounded-pill">Active</span>
                        </div>
                        <div class="bg-dark px-3 py-3">
                            <h5 class="text-white mb-0">
                                {{ $record->machinery_id ? (optional($record->machinery)->name ?? 'Unknown Machinery') : (optional($record->equipment)->name ?? 'Unknown Equipment') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info text-dark rounded-pill">{{ $record->machinery_id ? 'Machinery' : 'Equipment' }}</span>
                                <div class="fs-5 fw-bold text-success">{{ optional($record->user)->name ?? 'N/A' }}</div>
                            </div>
                            <p class="card-text small text-muted mb-3">
                                <i class="bi bi-calendar3 me-2"></i>Usage Date: {{ \Carbon\Carbon::parse($record->usage_date)->format('Y-m-d') }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <small class="text-muted">ID: {{ $record->id }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <!-- Pagination removed: all records are shown on one page -->
        </div>
    @endif

    <!-- Modal to Add Usage Record -->
    <div class="modal fade" id="addUsageRecordModal" tabindex="-1" aria-labelledby="addUsageRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="addUsageRecordModalLabel">Add New Usage Record</h5>
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
                    <form id="addUsageRecordForm" action="{{ route('usagerecords.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Worker</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Select Worker</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item_type" class="form-label">Item Type</label>
                            <select name="item_type" id="item_type" class="form-select" required>
                                <option value="">Select Item Type</option>
                                <option value="machinery">Machinery</option>
                                <option value="equipment">Equipment</option>
                            </select>
                            @error('item_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3" id="machinery_select" style="display: none;">
                            <label for="machinery_id" class="form-label">Machinery</label>
                            <select name="machinery_id" id="machinery_id" class="form-select">
                                <option value="">Select Machinery</option>
                                @foreach($machineries as $machinery)
                                    <option value="{{ $machinery->id }}">{{ $machinery->name }}</option>
                                @endforeach
                            </select>
                            @error('machinery_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3" id="equipment_select" style="display: none;">
                            <label for="equipment_id" class="form-label">Equipment</label>
                            <select name="equipment_id" id="equipment_id" class="form-select">
                                <option value="">Select Equipment</option>
                                @foreach($equipment as $equip)
                                    <option value="{{ $equip->id }}">{{ $equip->name }}</option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="usage_date" class="form-label">Usage Date</label>
                            <input type="datetime-local" name="usage_timestamps" id="usage_date" class="form-control" required>
                            @error('usage_timestamps')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="addSubmitButton">
                                <i class="bi bi-check-circle me-1"></i> Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Usage Record -->
    <div class="modal fade" id="editUsageRecordModal" tabindex="-1" aria-labelledby="editUsageRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-lg border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="editUsageRecordModalLabel">Edit Usage Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <form id="editUsageRecordForm" action="{{ route('usagerecords.update', ':id') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_user_id" class="form-label">Worker</label>
                            <select name="user_id" id="edit_user_id" class="form-select" required>
                                <option value="">Select Worker</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_item_type" class="form-label">Item Type</label>
                            <select name="item_type" id="edit_item_type" class="form-select" required>
                                <option value="">Select Item Type</option>
                                <option value="machinery">Machinery</option>
                                <option value="equipment">Equipment</option>
                            </select>
                            @error('item_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3" id="edit_machinery_select" style="display: none;">
                            <label for="edit_machinery_id" class="form-label">Machinery</label>
                            <select name="machinery_id" id="edit_machinery_id" class="form-select">
                                <option value="">Select Machinery</option>
                                @foreach($machineries as $machinery)
                                    <option value="{{ $machinery->id }}">{{ $machinery->name }}</option>
                                @endforeach
                            </select>
                            @error('machinery_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3" id="edit_equipment_select" style="display: none;">
                            <label for="edit_equipment_id" class="form-label">Equipment</label>
                            <select name="equipment_id" id="edit_equipment_id" class="form-select">
                                <option value="">Select Equipment</option>
                                @foreach($equipment as $equip)
                                    <option value="{{ $equip->id }}">{{ $equip->name }}</option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_usage_date" class="form-label">Usage Date</label>
                            <input type="datetime-local" name="usage_timestamps" id="edit_usage_date" class="form-control" required>
                            @error('usage_timestamps')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="editSubmitButton">
                                <i class="bi bi-check-circle me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Record Details -->
    <div class="modal fade" id="recordDetailsModal" tabindex="-1" aria-labelledby="recordDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white border-bottom-0">
                    <h5 class="modal-title fw-bold" id="recordDetailsModalLabel">Usage Record Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="recordDetailsBody">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="recordDetailsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Worker</th>
                                    <th>Item Type</th>
                                    <th>Item Name</th>
                                    <th>Usage Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Details row will be injected here by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-warning" id="detailsEditButton">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </button>
                        <form id="detailsDeleteForm" action="" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

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
    .rounded-lg {
        border-radius: 0.75rem;
    }
    .record-card {
        cursor: pointer;
        position: relative;
    }
    .record-card button,
    .record-card .dropdown {
        position: relative;
        z-index: 2;
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
    #recordDetailsModal .modal-dialog {
        max-width: 800px;
    }
    #recordDetailsModal .modal-content {
        border-radius: 16px;
    }
    #recordDetailsModal .modal-body {
        background-color: #f8f9fa;
    }
    #recordDetailsModal .modal-header {
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }
    #recordDetailsTable th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 1rem;
    }
    #recordDetailsTable td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }
    #recordDetailsTable .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    #recordDetailsTable .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }
    #recordDetailsTable tbody tr {
        transition: background-color 0.2s ease;
    }
    #recordDetailsTable tbody tr:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    @media (max-width: 767px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        .card-header, .dropdown-item {
            min-height: 44px;
        }
        .card {
            -webkit-tap-highlight-color: transparent;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle machinery/equipment dropdowns in add modal
        const itemTypeSelect = document.getElementById('item_type');
        const machinerySelect = document.getElementById('machinery_select');
        const equipmentSelect = document.getElementById('equipment_select');

        if (itemTypeSelect) {
            itemTypeSelect.addEventListener('change', function() {
                machinerySelect.style.display = this.value === 'machinery' ? 'block' : 'none';
                equipmentSelect.style.display = this.value === 'equipment' ? 'block' : 'none';
                document.getElementById('machinery_id').value = '';
                document.getElementById('equipment_id').value = '';
            });
        }

        // Toggle machinery/equipment dropdowns in edit modal
        const editItemTypeSelect = document.getElementById('edit_item_type');
        const editMachinerySelect = document.getElementById('edit_machinery_select');
        const editEquipmentSelect = document.getElementById('edit_equipment_select');

        if (editItemTypeSelect) {
            editItemTypeSelect.addEventListener('change', function() {
                editMachinerySelect.style.display = this.value === 'machinery' ? 'block' : 'none';
                editEquipmentSelect.style.display = this.value === 'equipment' ? 'block' : 'none';
                document.getElementById('edit_machinery_id').value = '';
                document.getElementById('edit_equipment_id').value = '';
            });
        }

        // Record card click handler
        const recordCards = document.querySelectorAll('.record-card');
        recordCards.forEach(card => {
            card.addEventListener('click', function(event) {
                // Pass the card element directly to the details function
                viewRecordDetails(this);
            });
        });
    });

    function openRecordModal(record) {
        const form = document.getElementById('editUsageRecordForm');
        form.action = `/usagerecords/${record.id}`;

        document.getElementById('edit_user_id').value = record.user_id || '';
        document.getElementById('edit_item_type').value = record.item_type || '';
        document.getElementById('edit_machinery_id').value = record.machinery_id || '';
        document.getElementById('edit_equipment_id').value = record.equipment_id || '';
        document.getElementById('edit_usage_date').value = record.usage_date;

        // Show/hide dropdowns based on item_type
        document.getElementById('edit_machinery_select').style.display = record.item_type === 'machinery' ? 'block' : 'none';
        document.getElementById('edit_equipment_select').style.display = record.item_type === 'equipment' ? 'block' : 'none';
    }

    function viewRecordDetails(cardElement) {
        const recordData = {
            id: cardElement.dataset.recordId,
            user_id: cardElement.dataset.userId,
            machinery_id: cardElement.dataset.machineryId,
            equipment_id: cardElement.dataset.equipmentId,
            item_type: cardElement.dataset.itemType,
            usage_date: new Date(cardElement.dataset.date).toISOString().slice(0,10),
            user_name: cardElement.querySelector('.text-success').textContent.trim(),
            item_name: cardElement.querySelector('.bg-dark h5').textContent.trim()
        };

        const detailsBody = document.getElementById('recordDetailsBody');
        detailsBody.innerHTML = `
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="recordDetailsTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Worker</th>
                            <th>Item Type</th>
                            <th>Item Name</th>
                            <th>Usage Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${recordData.id}</td>
                            <td>${recordData.user_name}</td>
                            <td>${recordData.item_type.charAt(0).toUpperCase() + recordData.item_type.slice(1)}</td>
                            <td>${recordData.item_name}</td>
                            <td>${recordData.usage_date}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        
        const detailsModal = new bootstrap.Modal(document.getElementById('recordDetailsModal'));
        
        // Set up the delete form action
        document.getElementById('detailsDeleteForm').action = `/usagerecords/${recordData.id}`;

        // Set up the edit button
        document.getElementById('detailsEditButton').onclick = function() {
            detailsModal.hide();
            // We need a slight delay to prevent modal backdrop issues
            setTimeout(() => {
                openRecordModal(recordData);
                const editModal = new bootstrap.Modal(document.getElementById('editUsageRecordModal'));
                editModal.show();
            }, 400);
        };
        
        detailsModal.show();
    }
</script>
@endsection