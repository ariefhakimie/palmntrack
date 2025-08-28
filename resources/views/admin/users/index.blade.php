@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fw-bold mb-0">User Management</h1>
                                <p class="text-muted">Estate inventory management system</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-plus-circle me-2"></i> Add User
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

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">Add New User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="">Select Role</option>
                                    <option value="staff">Staff</option>
                                    <option value="worker">Worker</option>
                                </select>
                                @error('role')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="addUserForm" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Add User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">Edit User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Role</label>
                                <select name="role" id="edit_role" class="form-select" required>
                                    <option value="">Select Role</option>
                                    <option value="staff">Staff</option>
                                    <option value="worker">Worker</option>
                                </select>
                                @error('role')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_password" class="form-label">New Password (leave blank to keep current)</label>
                                <input type="password" name="password" id="edit_password" class="form-control">
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="editUserForm" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- User List -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse($users as $user)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="position-absolute end-0 top-0 m-2">
                            <span class="badge rounded-pill 
                                @if ($user->role === 'admin') bg-danger
                                @elseif ($user->role === 'staff') bg-warning text-dark
                                @else bg-success
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        
                        <div class="bg-dark px-3 py-3">
                            <h5 class="text-white mb-0">{{ $user->name }}</h5>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info text-dark rounded-pill">{{ $user->email }}</span>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning flex-grow-1" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger flex-grow-1" onclick="confirmDelete({{ $user->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-top-0">
                            <small class="text-muted">ID: {{ $user->id }}</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i> No users found.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function openEditModal(id, name, email, role) {
            const form = document.getElementById('editUserForm');
            form.action = `/admin/users/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                document.getElementById(`delete-form-${id}`).submit();
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
    </style>
@endsection