@extends('layouts.app')

@section('title', 'Field Supervisor Dashboard')

@section('content')
<div class="container py-4" style="max-width: 90%; width: 1600px;">
    <!-- Header -->
    <div class="text-center mb-4 p-4 p-md-5 rounded bg-gradient-to-r from-emerald-500 to-teal-700 text-white shadow-lg">
        <h1 class="fw-bold display-4 display-md-3">Staff Dashboard</h1>
        <p class="lead mb-0 fs-5 fs-md-4">Efficiently manage your estate resources</p>
    </div>



    <!-- Row 1: Commodities + Machinery -->
    <div class="row justify-content-center g-4 g-md-5 g-lg-6 mb-4">
        <!-- Commodities -->
        <div class="col-12 col-md-4 d-flex align-items-stretch">
            <a href="{{ route('commodities.fsindex') }}" class="text-decoration-none w-100">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all rounded-lg overflow-hidden clickable-card">
                    <div class="card-header bg-success bg-opacity-10 text-success border-0 py-3 py-md-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-box-seam fs-3 me-3"></i>
                            <span class="fw-bold fs-4">Commodities</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4 p-md-5">
                        <p class="mb-0 fs-5">Track estate commodities</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Machinery -->
        <div class="col-12 col-md-4 d-flex align-items-stretch">
            <a href="{{ route('machinery.fsindex') }}" class="text-decoration-none w-100">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all rounded-lg overflow-hidden clickable-card">
                    <div class="card-header bg-primary bg-opacity-10 text-primary border-0 py-3 py-md-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-gear-wide-connected fs-3 me-3"></i>
                            <span class="fw-bold fs-4">Machinery</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4 p-md-5">
                        <p class="mb-0 fs-5">Manage estate machinery</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Row 2: Equipment + Usage Records -->
    <div class="row justify-content-center g-4 g-md-5 g-lg-6 mb-4">
        <!-- Equipment -->
        <div class="col-12 col-md-4 d-flex align-items-stretch">
            <a href="{{ route('equipment.fsindex') }}" class="text-decoration-none w-100">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all rounded-lg overflow-hidden clickable-card">
                    <div class="card-header bg-warning bg-opacity-10 text-warning border-0 py-3 py-md-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-tools fs-3 me-3"></i>
                            <span class="fw-bold fs-4">Equipment</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4 p-md-5">
                        <p class="mb-0 fs-5">Monitor equipment stock</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Usage Records -->
        <div class="col-12 col-md-4 d-flex align-items-stretch">
            <a href="{{ route('usagerecords.index') }}" class="text-decoration-none w-100">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all rounded-lg overflow-hidden clickable-card">
                    <div class="card-header bg-info bg-opacity-10 text-info border-0 py-3 py-md-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clipboard-data fs-3 me-3"></i>
                            <span class="fw-bold fs-4">Usage Records</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column text-center p-4 p-md-5">
                        <p class="mb-0 fs-5">Review activity logs</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Style -->
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .hover-shadow:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        }
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .bg-gradient-to-r {
        background: linear-gradient(90deg, #2e7d32 0%, #1b5e20 100%);
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .clickable-card {
        cursor: pointer;
        position: relative;
    }

    .clickable-card:after {
        content: '';
        position: absolute;
        bottom: 1.5rem;
        right: 1.5rem;
        width: 32px;
        height: 32px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='bi bi-arrow-right-circle' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.5;
    }

    @media (max-width: 767px) {
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }

        .card-header, .dropdown-item {
            min-height: 48px;
        }

        .card {
            -webkit-tap-highlight-color: transparent;
        }
    }

    .card-body p {
        color: #6c757d;
        font-weight: 500;
    }
</style>
@endsection
