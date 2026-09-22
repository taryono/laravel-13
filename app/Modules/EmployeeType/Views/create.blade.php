{{ Form::open(['method' => 'POST', 'route' => ['employee_leave.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row">

    {{-- Code --}}
    <div class="col-md-6 mb-3">
        <label for="code" class="form-label">Code<span class="text-danger">*</span></label>
        {{ Form::text('code', old('code'), [
            'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
            'required' => 'required',
            'id' => 'code',
        ]) }}
        @if ($errors->has('code'))
            <div class="invalid-feedback">{{ $errors->first('code') }}</div>
        @endif
    </div>

    {{-- Name --}}
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
        {{ Form::text('name', old('name'), [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'required' => 'required',
            'id' => 'name',
        ]) }}
        @if ($errors->has('name'))
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>
{{ Form::close() }}
