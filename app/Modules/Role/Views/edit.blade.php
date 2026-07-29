{{ Form::model($role, ['method' => 'PUT', 'route' => ['role.update', $role->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@forelse ($forms as $key => $form)
    @if(!isset($form['label'])) @continue @endif
    <div class="form-group"> 
        <label for="{{ $key }}">
            {{ say($form['label']) }}
        </label> 
        {!! $form['content'] !!} 
    </div> 
    @empty 
    <p>
        {{ say('Data Tidak Ditemukan') }}
    </p>

@endforelse
{{ Form::close() }}