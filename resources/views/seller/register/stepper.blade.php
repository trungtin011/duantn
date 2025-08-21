<div class="bg-white rounded-2xl border border-gray-100 p-8 mb-8">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Đăng ký Shop</h2>
            <p class="text-gray-600">Hoàn thành các bước để tạo shop của bạn</p>
        </div>

        <!-- Stepper -->
        <div class="relative">
            <!-- Progress Bar -->
            <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 rounded-full">
                <div class="h-full bg-gradient-to-r from-orange-400 to-red-500 rounded-full transition-all duration-500" 
                     id="progress-bar" 
                     style="width: 0%"></div>
            </div>

            <!-- Steps -->
            <div class="relative flex justify-between items-center">
                @php
                    $steps = [
                        ['title' => 'Thông tin Shop', 'icon' => 'fas fa-store', 'description' => 'Thông tin cơ bản'],
                        ['title' => 'Thông tin Thuế', 'icon' => 'fas fa-file-invoice', 'description' => 'Thông tin kinh doanh'],
                        ['title' => 'Định danh', 'icon' => 'fas fa-id-card', 'description' => 'Xác thực danh tính'],
                        ['title' => 'Hoàn tất', 'icon' => 'fas fa-check-circle', 'description' => 'Hoàn thành']
                    ];
                @endphp
                
                @foreach ($steps as $index => $step)
                    <div class="stepper-step flex flex-col items-center relative z-10">
                        <!-- Step Circle -->
                        <div class="step-dot w-12 h-12 rounded-full border-4 border-gray-200 bg-white flex items-center justify-center transition-all duration-300 mb-3">
                            <i class="{{ $step['icon'] }} text-gray-400 text-lg transition-all duration-300"></i>
                        </div>
                        
                        <!-- Step Label -->
                        <div class="text-center max-w-24">
                            <div class="step-label font-semibold text-sm text-gray-600 transition-all duration-300">
                                {{ $step['title'] }}
                            </div>
                            <div class="step-description text-xs text-gray-400 mt-1 hidden sm:block">
                                {{ $step['description'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.stepper-step.active .step-dot {
    border-color: #f97316;
    background: linear-gradient(135deg, #f97316, #ef4444);
    box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.2);
}

.stepper-step.active .step-dot i {
    color: white;
}

.stepper-step.done .step-dot {
    border-color: #10b981;
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
}

.stepper-step.done .step-dot i {
    color: white;
}

.stepper-step.active .step-label {
    color: #f97316;
    font-weight: 700;
}

.stepper-step.done .step-label {
    color: #10b981;
    font-weight: 700;
}

.stepper-step.disabled .step-dot {
    border-color: #e5e7eb;
    background: white;
}

.stepper-step.disabled .step-dot i {
    color: #9ca3af;
}

.stepper-step.disabled .step-label {
    color: #9ca3af;
}
</style>

<script>
function updateStepper(currentStep) {
    const steps = document.querySelectorAll('.stepper-step');
    const progressBar = document.getElementById('progress-bar');
    
    // Calculate progress percentage
    const progressPercentage = (currentStep / (steps.length - 1)) * 100;
    progressBar.style.width = progressPercentage + '%';
    
    steps.forEach((step, index) => {
        const dot = step.querySelector('.step-dot');
        const label = step.querySelector('.step-label');
        const description = step.querySelector('.step-description');
        
        // Remove all classes
        step.className = 'stepper-step flex flex-col items-center relative z-10';
        dot.className = 'step-dot w-12 h-12 rounded-full border-4 border-gray-200 bg-white flex items-center justify-center transition-all duration-300 mb-3';
        label.className = 'step-label font-semibold text-sm text-gray-600 transition-all duration-300';
        
        if (index < currentStep) {
            // Completed steps
            step.classList.add('done');
            dot.classList.add('done');
            label.classList.add('done');
        } else if (index === currentStep) {
            // Current step
            step.classList.add('active');
            dot.classList.add('active');
            label.classList.add('active');
        } else {
            // Future steps
            step.classList.add('disabled');
            dot.classList.add('disabled');
            label.classList.add('disabled');
        }
    });
}
</script>