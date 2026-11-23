{{ Form::model($journal_entry, ['method' => 'PUT', 'route' => ['journal_entry.update', $journal_entry->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
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
