{{ Form::model($cmsPost, ['method' => 'PUT', 'route' => ['cms_post.update', $cmsPost->id], 'class' => 'form-horizontal form-validation', 'id' => 'formCmsPost', 'enctype' => 'multipart/form-data']) }}
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
