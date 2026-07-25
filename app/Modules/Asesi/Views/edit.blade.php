<section class="content">

    <div class="container-fluid">
        {{ Form::model($asesi, ['method' => 'PUT', 'route' => ['asesi.update', $asesi->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

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
        {{ Form::close() }}
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
<script>
    $(function() {
        spreadData({!! json_encode($asesi) !!});
        $('.selectpicker').selectpicker('refresh');
    });
</script>
