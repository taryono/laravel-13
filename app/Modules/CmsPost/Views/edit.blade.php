{{ Form::model($cmsPost, ['method' => 'PUT', 'route' => ['cms_post.update', $cmsPost->id], 'class' => 'form-horizontal form-validation', 'id' => 'formCmsPost', 'enctype' => 'multipart/form-data']) }}
@include('CmsPost::form')
{{ Form::close() }}
