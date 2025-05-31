<!-- Wizard Steps -->
<div class="d-flex justify-content-between align-items-center mb-5 position-relative wizard-progress">
    <div class="position-absolute w-100 h-2 bg-light rounded-pill" style="top: 50%; transform: translateY(-50%); z-index: 0;"></div>
    <div class="position-absolute h-2 bg-primary rounded-pill progress-bar" style="width: {{ ($step - 1) * 33.33 }}%; top: 50%; transform: translateY(-50%); z-index: 1; transition: width 0.5s ease;"></div>

    <!-- Step 1 -->
    <div class="wizard-step text-center rounded-4 shadow-sm p-4 position-relative z-2 step-card {{ $step >= 1 ? 'active' : '' }}" 
         data-step="1" 
         style="flex: 1; margin: 0 10px; transition: all 0.3s ease; {{ $step >= 1 ? 'background: #39aba0 !important; color: white;' : 'background: white;' }}">
        <div class="step-icon-wrapper mb-3">
            <i class="bi bi-geo-alt-fill fs-3 {{ $step >= 1 ? 'text-white' : 'text-primary' }}"></i>
        </div>
        <h5 class="mb-1 fw-semibold" style="font-size: 14px">Get information</h5>
        <p class="small {{ $step >= 1 ? 'text-white' : 'text-muted' }} mb-0" style="font-size: 10px;">Choose your journey</p>
    </div>

    <!-- Step 2 -->
    <div class="wizard-step text-center rounded-4 shadow-sm p-4 position-relative z-2 step-card {{ $step >= 2 ? 'active' : '' }}" 
         data-step="2" 
         style="flex: 1; margin: 0 10px; transition: all 0.3s ease; {{ $step >= 2 ? 'background: #39aba0 !important; color: white;' : 'background: white;' }}">
        <div class="step-icon-wrapper mb-3">
            <i class="bi bi-calendar-check fs-3 {{ $step >= 2 ? 'text-white' : 'text-primary' }}"></i>
        </div>
        <h5 class="mb-1 fw-semibold" style="font-size: 14px">Date & PAX</h5>
        <p class="small {{ $step >= 2 ? 'text-white' : 'text-muted' }} mb-0" style="font-size: 10px;">Set your schedule</p>
    </div>

    <!-- Step 3 -->
    <div class="wizard-step text-center rounded-4 shadow-sm p-4 position-relative z-2 step-card {{ $step >= 3 ? 'active' : '' }}" 
         data-step="3" 
         style="flex: 1; margin: 0 10px; transition: all 0.3s ease; {{ $step >= 3 ? 'background: #39aba0 !important; color: white;' : 'background: white;' }}">
        <div class="step-icon-wrapper mb-3">
            <i class="bi bi-building fs-3 {{ $step >= 3 ? 'text-white' : 'text-primary' }}"></i>
        </div>
        <h5 class="mb-1 fw-semibold" style="font-size: 14px">Locations & Hotels</h5>
        <p class="small {{ $step >= 3 ? 'text-white' : 'text-muted' }} mb-0" style="font-size: 10px;">Find your stay</p>
    </div>

    <!-- Step 4 -->
    <div class="wizard-step text-center rounded-4 shadow-sm p-4 position-relative z-2 step-card {{ $step >= 4 ? 'active' : '' }}" 
         data-step="4" 
         style="flex: 1; margin: 0 10px; transition: all 0.3s ease; {{ $step >= 4 ? 'background: #39aba0 !important; color: white;' : 'background: white;' }}">
        <div class="step-icon-wrapper mb-3">
            <i class="bi bi-check-circle fs-3 {{ $step >= 4 ? 'text-white' : 'text-primary' }}"></i>
        </div>
        <h5 class="mb-1 fw-semibold" style="font-size: 14px">Complete Guide</h5>
        <p class="small {{ $step >= 4 ? 'text-white' : 'text-muted' }} mb-0" style="font-size: 10px;">Finalize your plan</p>
    </div>
</div>
