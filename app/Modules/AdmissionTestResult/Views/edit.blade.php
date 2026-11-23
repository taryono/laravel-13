{{ Form::model($admission - test - result, ['method' => 'PUT', 'route' => ['admission_test_result.update', $admission - test - result->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
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
