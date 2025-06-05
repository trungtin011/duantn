<div class="px-4 py-4">
    <div class="flex justify-between items-center position-relative" style="margin-bottom:30px;">
        @php
            $steps = ['Thông tin Shop', 'Cài đặt vận chuyển', 'Thông tin thuế', 'Thông tin định danh', 'Hoàn tất'];
        @endphp
        @foreach ($steps as $index => $step)
            <div class="stepper-step text-center flex-fill">
                <div class="step-dot"></div>
                <div class="step-label">{{ $step }}</div>
            </div>
            @if ($index < count($steps) - 1)
                <div class="stepper-line"></div>
            @endif
        @endforeach
    </div>
</div>

{{-- hr --}}
<hr class="m-0 p-0">