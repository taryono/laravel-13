 <section class="content">
     <div class="container-fluid">
         {{ Form::open(['method' => 'POST', 'route' => ['asesi.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
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
 <script>
     $(function() {
         $('.selectpicker').selectpicker('refresh');
     });
 </script>
