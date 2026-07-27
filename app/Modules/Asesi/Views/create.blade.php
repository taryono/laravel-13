<div class="card card-primary">
{{ Form::open(['method' => 'POST', 'route' => ['asesi.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <h3 class="card-title">{{ say('Asesi') }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                @dump($forms)
                @forelse ($forms as $key => $form)
                 @if(!isset($form['label'])) @continue @endif
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
