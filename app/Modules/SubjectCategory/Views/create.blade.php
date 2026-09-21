{{ Form::open(['method' => 'POST', 'route' => ['subject_category.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="col-md-6 mb-3">
            @forelse ($forms as $key => $form)
                <div class="form-group">
                    <label for="name">{{ say($form['label']) }}</label>
                    {!! $form['content'] !!}
                </div>
            @empty
                <p>{{ say('Data Tidak Ditemukan') }}</p>
            @endforelse
        </div>
    </div>
{{ Form::close() }}  
