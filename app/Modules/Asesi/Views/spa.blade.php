<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <h4>{{ $title }}</h4>
            </h5>
        </div>
        <div class="card-body list-content">
            @include("{$path}::list")
        </div>
    </div>
</div>

@if ($company->is_spa)
@endif
