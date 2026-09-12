{{ Form::open(['method' => 'POST', 'route' => ['reference_value.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">Reference Type</label>
        {{ Form::select('reference_type_id', $reference_types, null, ['class' => 'form-control', 'id' => 'reference_type_id', 'required' => 'required']) }}
        <div class="invalid-feedback" id="err_reference_type_id"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Code</label>
        <input type="text" class="form-control" id="value_code" required>
        <div class="invalid-feedback" id="err_value_code"></div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" id="value_name" required>
        <div class="invalid-feedback" id="err_value_name"></div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" id="value_description" rows="2"></textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Badge Color</label>
        <select class="form-select" id="value_badge_color">
            <option value="primary">primary</option>
            <option value="secondary" selected>secondary</option>
            <option value="success">success</option>
            <option value="danger">danger</option>
            <option value="warning">warning</option>
            <option value="info">info</option>
            <option value="dark">dark</option>
            <option value="light">light</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Badge Class</label>
        <input type="text" class="form-control" id="value_badge_class" placeholder="badge bg-secondary">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Text Color</label>
        <input type="text" class="form-control" id="value_text_color" placeholder="text-white">
    </div>
</div>

<div class="row align-items-center">
    <div class="col-md-4 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number" class="form-control" id="value_sort_order" value="0">
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" id="value_is_system">
            <label class="form-check-label" for="value_is_system">Is System</label>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" id="value_is_active" checked>
            <label class="form-check-label" for="value_is_active">Is Active</label>
        </div>
    </div>
</div>
{{ Form::close() }}
