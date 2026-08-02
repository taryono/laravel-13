@props(['duration' => 5000])

@if(session()->hasAny(['success', 'error', 'warning', 'info']))
    <div id="flash-messages" data-auto-dismiss="{{ $duration }}" 
         class="position-fixed top-0 end-0 p-3" style="z-index: 9999; max-width: 400px;">
        @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $key => $class)
            @if(session($key))
                <div class="alert alert-{{ $class }} alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-start">
                        <div class="me-2">
                            @if($key === 'success')
                                <i class="fas fa-check-circle fa-lg"></i>
                            @elseif($key === 'error')
                                <i class="fas fa-exclamation-circle fa-lg"></i>
                            @elseif($key === 'warning')
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            @else
                                <i class="fas fa-info-circle fa-lg"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            {{ session($key) }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach
    </div>
@endif