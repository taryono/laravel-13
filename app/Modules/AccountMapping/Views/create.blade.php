{{ Form::open(['method' => 'POST', 'route' => ['account_mapping.store'], 'class' => 'form-horizontal form-validation', 'id' => 'formAccountMapping', 'enctype' => 'multipart/form-data']) }}
@include('AccountMapping::_form')
{{ Form::close() }}

<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
