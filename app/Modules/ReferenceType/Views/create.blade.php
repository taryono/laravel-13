{{ Form::open(['method' => 'POST', 'route' => ['reference_type.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Code</label>
        <input type="text" class="form-control" id="code" required>
        <div class="invalid-feedback" id="err_code"></div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" id="name" required>
        <div class="invalid-feedback" id="err_name"></div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" id="description" rows="2"></textarea>
</div>

<div class="row align-items-center">
    <div class="col-md-6 mb-3">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" id="is_system" checked>
            <label class="form-check-label" for="is_system">Is System</label>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" id="is_active" checked>
            <label class="form-check-label" for="is_active">Is Active</label>
        </div>
    </div>
</div>
{{ Form::close() }}
