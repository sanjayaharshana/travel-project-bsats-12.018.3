@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Generated Travel Routes</h4>
                    <a href="{{ route('tour.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Route
                    </a>
                </div>
                <div class="card-body">
                    @if($routes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Route Name</th>
                                        <th>Start Location</th>
                                        <th>End Location</th>
                                        <th>Return Location</th>
                                        <th>Total Routes</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes as $route)
                                        <tr>
                                            <td>
                                                <strong>{{ $route->route_name }}</strong>
                                            </td>
                                            <td>{{ $route->startLocation->location_name ?? 'N/A' }}</td>
                                            <td>{{ $route->endLocation->location_name ?? 'N/A' }}</td>
                                            <td>{{ $route->returnLocation->location_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ count($route->routes_data) }} routes
                                                </span>
                                            </td>
                                            <td>{{ $route->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('tour.show-routes', $route->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-route" 
                                                            data-route-id="{{ $route->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $routes->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-route fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No routes generated yet</h5>
                            <p class="text-muted">Create your first travel route to get started!</p>
                            <a href="{{ route('tour.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Route
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this route? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let routeIdToDelete = null;
    
    // Delete route button click
    $('.delete-route').on('click', function() {
        routeIdToDelete = $(this).data('route-id');
        $('#deleteModal').modal('show');
    });
    
    // Confirm delete
    $('#confirmDelete').on('click', function() {
        if (!routeIdToDelete) return;
        
        $.ajax({
            url: `/tour/routes/${routeIdToDelete}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to delete route');
                }
            },
            error: function() {
                alert('An error occurred while deleting the route');
            }
        });
        
        $('#deleteModal').modal('hide');
    });
});
</script>
@endpush 