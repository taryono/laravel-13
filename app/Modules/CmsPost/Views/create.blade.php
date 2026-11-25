{{ Form::open(['method' => 'POST', 'route' => ['cms_post.store'], 'class' => 'form-horizontal form-validation', 'id' => 'formCmsPost', 'enctype' => 'multipart/form-data']) }}
@include('CmsPost::form')
{{ Form::close() }}
