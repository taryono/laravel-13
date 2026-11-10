{{ Form::open(['method' => 'POST', 'route' => ['curriculum.update', $curriculum->id], 'class' => 'form-horizontal']) }}
@method('PUT')

<div class="row g-3">

    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-0">Curriculum Info</h5>
    </div>

    <div class="col-md-6">
        {{ Form::label('organization_id', 'Organization', ['class' => 'form-label']) }}
        {{ Form::select('organization_id', $organizations ?? [], old('organization_id', $curriculum->organization_id), ['class' => 'form-select' . ($errors->has('organization_id') ? ' is-invalid' : ''), 'placeholder' => '-- Select Organization --']) }}
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('type', 'Type', ['class' => 'form-label']) }}
        {{ Form::select(
            'type',
            [
                'national' => 'National',
                'international' => 'International',
                'local' => 'Local',
                'hybrid' => 'Hybrid',
            ],
            old('type', $curriculum->type),
            ['class' => 'form-select' . ($errors->has('type') ? ' is-invalid' : ''), 'placeholder' => '-- Select Type --'],
        ) }}
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('name', 'Name', ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $curriculum->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'maxlength' => 100]) }}
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('code', 'Code', ['class' => 'form-label']) }}
        {{ Form::text('code', old('code', $curriculum->code), ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''), 'maxlength' => 20]) }}
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        {{ Form::label('start_year', 'Start Year', ['class' => 'form-label']) }}
        {{ Form::number('start_year', old('start_year', $curriculum->start_year), ['class' => 'form-control' . ($errors->has('start_year') ? ' is-invalid' : ''), 'min' => 1901, 'max' => 2155]) }}
        @error('start_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        {{ Form::label('end_year', 'End Year', ['class' => 'form-label']) }}
        {{ Form::number('end_year', old('end_year', $curriculum->end_year), ['class' => 'form-control' . ($errors->has('end_year') ? ' is-invalid' : ''), 'min' => 1901, 'max' => 2155]) }}
        @error('end_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            {{ Form::hidden('is_active', 0) }}
            {{ Form::checkbox('is_active', 1, old('is_active', $curriculum->is_active), ['class' => 'form-check-input' . ($errors->has('is_active') ? ' is-invalid' : ''), 'id' => 'is_active']) }}
            {{ Form::label('is_active', 'Active', ['class' => 'form-check-label']) }}
            @error('is_active')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        {{ Form::label('description', 'Description', ['class' => 'form-label']) }}
        {{ Form::textarea('description', old('description', $curriculum->description), ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'rows' => 3]) }}
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{ Form::close() }}
