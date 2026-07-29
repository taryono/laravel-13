<div class="card card-primary">
{{ Form::open(['method' => 'POST', 'route' => ['module.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <h3 class="card-title">{{ say('Module') }}</h3>
    </div>
    <div class="card-body">
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
    </div>

{{ Form::close() }} 
</div>
