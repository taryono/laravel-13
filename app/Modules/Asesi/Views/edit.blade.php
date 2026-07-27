@extends('layouts.app')

@section('content')
<div class="container">

<div class="card card-primary"> 
    <form
        method="POST"
        action="{{ route('asesi.update', $asesi->id) }}"
        class="form-horizontal"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="card-header">
            <h3 class="card-title">
                {{ say('Asesi') }}
            </h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

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

                </div>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan
            </button>

            <a
                href="{{ route('document.index') }}"
                class="btn btn-secondary"
            >
                Batal
            </a>

        </div>

    </form>

</div>

</div>
@endsection