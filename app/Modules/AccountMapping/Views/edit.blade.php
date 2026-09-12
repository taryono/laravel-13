{{ Form::model($account_mapping, ['method' => 'PUT', 'route' => ['account_mapping.update', $account_mapping->id], 'class' => 'form-horizontal', 'id' => 'formAccountMapping', 'enctype' => 'multipart/form-data']) }}
    @include('AccountMapping::_form')
{{ Form::close() }}
 <script src="{{asset('vendor/select2/select2.min.js')}}"></script>

 <script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>