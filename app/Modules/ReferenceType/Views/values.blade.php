 <div class="container">
     <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Daftar ReferenceValue</h5>


         </div>
         <div class="card-body">
             @if (session('success'))
                 <div class="alert alert-success">{{ session('success') }}</div>
             @endif

             <div class="table-responsive">
                 <table id="reference_value_table" class="table table-bordered table-striped w-100">
                     <thead>
                         <tr>
                             <th>No</th>
                             <th>Code</th>
                             <th>Name</th>
                             <th>Description</th>
                             <th>Is System</th>
                             <th>Is Active</th>
                             <th>Actions</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($values as $value)
                             <tr>
                                 <td>{{ $loop->iteration }}</td>
                                 <td>{{ $value->code }}</td>
                                 <td>{{ $value->name }}</td>
                                 <td>{{ $value->description }}</td>
                                 <td>{{ $value->is_system ? 'Yes' : 'No' }}</td>
                                 <td>{{ $value->is_active ? 'Yes' : 'No' }}</td>
                                 <td>
                                     <button type="button" class="btn btn-sm btn-danger delete-button"
                                         data-confirm-delete="true" data-is_execute_drawer="true"
                                         data-url="{{ route('reference_value.destroy', $value->id) }}" title="Hapus">
                                         <span class="material-symbols-outlined">delete</span>
                                     </button>
                                 </td>
                             </tr>
                         @endforeach
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>
