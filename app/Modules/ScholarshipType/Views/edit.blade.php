{{ Form::model($scholarship_type, ['method' => 'PUT', 'route' => ['scholarship_type.update', $scholarship_type->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    @forelse ($forms as $key => $form)
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="name">{{ $form['label'] }}</label>
                {!! $form['content'] !!}
            </div>
        </div>
    @empty
        <p>{{ say('Data Tidak Ditemukan') }}</p>
    @endforelse
</div>
{{ Form::close() }}
