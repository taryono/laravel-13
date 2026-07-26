@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Daftar Asesor</h5>
        </div>
        <div class="card-body">
            {!! $html->table(['class' => 'table table-bordered table-striped w-100']) !!}
        </div>
    </div>
</div>

{{-- ✅ PENTING: Taruh LANGSUNG di sini, JANGAN di @push --}}
{!! $html->scripts() !!}
@endsection