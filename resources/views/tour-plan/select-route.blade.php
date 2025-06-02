@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        /* Wizard Container */
        .wizard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        /* Wizard Steps */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
            padding: 0 1rem;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }

        .wizard-step {
            flex: 1;
            text-align: center;
            padding: 1.5rem 1rem;
            background: #fff;
            border-radius: 8px;
            margin: 0 10px;
            position: relative;
            z-index: 1;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .wizard-step i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #6c757d;
        }

        .wizard-step h4 {
            font-size: 1rem;
            margin: 0;
            color: #495057;
            font-weight: 500;
        }

        .wizard-step.active {
            background: #0d6efd;
            transform: translateY(-5px);
        }

        .wizard-step.active i,
        .wizard-step.active h4 {
            color: #fff;
        }

        .wizard-step.completed {
            background: #198754;
        }

        .wizard-step.completed i,
        .wizard-step.completed h4 {
            color: #fff;
        }

        /* Content Area */
        .wizard-content {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
        }

        .step-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .step-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Map Container */
        .map-container {
            height: 400px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        /* Route List */
        .route-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .route-item {
            background: #fff;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .route-item:hover {
            transform: translateY(-2px);
        }

        /* Location Suggestions */
        .location-suggestions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .location-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Navigation Buttons */
        .wizard-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .wizard-navigation .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .wizard-navigation .btn-primary {
            background: #0d6efd;
            border: none;
        }

        .wizard-navigation .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }

        .wizard-navigation .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .wizard-navigation .btn-secondary:hover {
            background: #5c636a;
            transform: translateY(-2px);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            background: #fff;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card h4 {
            color: #495057;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        /* Scrollbar */
        .route-list::-webkit-scrollbar {
            width: 8px;
        }

        .route-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .route-list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .route-list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .wizard-container {
                padding: 1rem;
                margin: 1rem;
            }

            .wizard-steps {
                flex-direction: column;
                gap: 1rem;
                margin-bottom: 2rem;
            }

            .wizard-steps::before {
                display: none;
            }

            .wizard-step {
                margin: 0;
            }

            .wizard-content {
                padding: 1rem;
            }

            .map-container {
                height: 300px;
            }

            .location-suggestions {
                grid-template-columns: 1fr;
            }

            .wizard-navigation {
                flex-direction: column;
                gap: 1rem;
            }

            .wizard-navigation .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="mt-5">
            @include('dashboard.welcome')
        </div>
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        @include('tour-plan.steps.page.step2')
                    </div>
                </div>
            </div>
            @include('tour-plan.steps.components.right-side-pane')
        </div>
    </div>


    <style>
        /* Existing styles ... */

        /* New styles for right pane */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        }

        .list-group-item {
            border: none;
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .list-group-item i {
            font-size: 1.1rem;
        }

        .trip-summary {
            font-size: 0.95rem;
        }

        .hotel-suggestions {
            max-height: 300px;
            overflow-y: auto;
        }

        .weather-forecast {
            min-height: 150px;
        }

        /* Custom scrollbar for hotel suggestions */
        .hotel-suggestions::-webkit-scrollbar {
            width: 4px;
        }

        .hotel-suggestions::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .hotel-suggestions::-webkit-scrollbar-thumb {
            background: #0d6efd;
            border-radius: 2px;
        }

        .hotel-suggestions::-webkit-scrollbar-thumb:hover {
            background: #0b5ed7;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .col-lg-4 {
                margin-top: 2rem;
            }
        }

        /* Animation for cards */
        .card {
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered animation for cards */
        .col-lg-4 .card:nth-child(1) { animation-delay: 0.1s; }
        .col-lg-4 .card:nth-child(2) { animation-delay: 0.2s; }
        .col-lg-4 .card:nth-child(3) { animation-delay: 0.3s; }
        .col-lg-4 .card:nth-child(4) { animation-delay: 0.4s; }
    </style>

    @section('scripts')

    @endsection
