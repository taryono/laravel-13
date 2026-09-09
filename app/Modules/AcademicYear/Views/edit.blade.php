{{ Form::model($academic-year, ['method' => 'PUT', 'route' => ['academic_year.update', $academic-year->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="col-md-6 mb-3">
            @forelse ($forms as $key => $form)
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>{{ say($form['label']) }}</label>
                        {!! $form['content'] !!}
                    </div>
                </div>
            @empty
                <p>{{ say('Data Tidak Ditemukan') }}</p>
            @endforelse
        </div> 
    </div> 
{{ Form::close() }}
<!-- /.row -->