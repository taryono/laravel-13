{{ Form::model($cmsPost ?? $post, ['method' => 'PUT', 'route' => ['cms_post.update', ($cmsPost ?? $post)->id], 'class' => 'form-horizontal form-validation', 'id' => 'formCmsPost', 'enctype' => 'multipart/form-data']) }}
@include('CmsPost::form')
{{ Form::close() }}
