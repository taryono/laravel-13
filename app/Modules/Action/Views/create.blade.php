{{ Form::open(['method' => 'POST', 'route' => ['action.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

    <div class="row">
        <div class="col-md-6 mb-3">
            @forelse ($forms as $key => $form)
                <div class="form-group">
                    {{-- FIX: 'for' sekarang mengikuti $key, bukan hardcode 'name' --}}
                    <label for="{{ $key }}">{{ say($form['label']) }}</label>
                    {!! $form['content'] !!}

                    @if (!empty($form['comment']))
                        <small class="form-text text-muted">{{ say($form['comment']) }}</small>
                    @endif

                    {{-- Tampilkan error validasi per-field --}}
                    @error($key)
                        <span class="text-danger d-block">{{ $message }}</span>
                    @enderror
                </div>
            @empty
                <p>{{ say('Data Tidak Ditemukan') }}</p>
            @endforelse
        </div>

        {{-- Field yang dikelompokkan (setGroups) sekarang tidak hilang lagi,
             dan dirender di section terpisah --}}
        @if (!empty($groupedForms))
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">{{ say('Informasi Tambahan') }}</div>
                    <div class="card-body">
                        @foreach ($groupedForms as $key => $form)
                            <div class="form-group">
                                <label for="{{ $key }}">{{ say($form['label']) }}</label>
                                {!! $form['content'] !!}

                                @if (!empty($form['comment']))
                                    <small class="form-text text-muted">{{ say($form['comment']) }}</small>
                                @endif

                                @error($key)
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div> 
{{ Form::close() }}