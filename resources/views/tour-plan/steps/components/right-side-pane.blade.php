<!-- Right Side Pane -->
<div class="col-lg-3">
    <div class="sticky-sidebar" style="position: sticky; top: 50px;">
        <!-- Quick Actions Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <i class="bi bi-lightning-charge-fill text-primary me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-bookmark-plus me-3 text-primary"></i>
                        Save as Draft
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-share me-3 text-primary"></i>
                        Share Plan
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                        <i class="bi bi-printer me-3 text-primary"></i>
                        Print Itinerary
                    </a>
                </div>
            </div>
        </div>

        <!-- Trip Summary Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <i class="bi bi-journal-text text-primary me-2"></i>
                    Trip Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="trip-summary">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Duration</span>
                        <span class="fw-semibold" id="trip-duration">-</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Distance</span>
                        <span class="fw-semibold" id="trip-distance">-</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Budget</span>
                        <span class="fw-semibold" id="trip-budget">-</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Travelers</span>
                        <span class="fw-semibold" id="trip-travelers">-</span>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<style>
.sticky-sidebar {
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
}

.sticky-sidebar::-webkit-scrollbar {
    width: 6px;
}

.sticky-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sticky-sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

@media (max-width: 991.98px) {
    .sticky-sidebar {
        position: relative !important;
        top: 0 !important;
        max-height: none;
    }
}
</style>
