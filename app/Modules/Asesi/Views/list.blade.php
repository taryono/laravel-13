<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <h4>{{ $title }}</h4>
                </h5>
            </div>
            <div class="card-body list-content">
                <div class="row">
                    <div class="col-12 table-responsive theme"
                        data-theme="{{ auth()->check() ? auth()->user()->theme : 'light' }}">

                        <table id="asesi" class="table table-bordered table-striped standard table-sm"
                            data-filename="Asesi" data-title="Data Asesi" data-route={{ route('asesi.draw') }}
                            style="width:100%;">
                            <thead>
                                 <tr>
                                    <th data-name="id" nowrap="nowrap">{{ say('No') }} </th>
                                    <th data-name="name" nowrap="nowrap">{{ say('Nama') }} </th> 
                                    <th data-name="register_date" nowrap="nowrap">{{ say('Tgl Daftar') }} </th>
                                    <th data-name="cellphone" nowrap="nowrap">{{ say('No Handphone') }}</th>
                                    <th data-name="email" nowrap="nowrap">{{ say('Email') }}</th>
                                    <th data-name="verification_status" nowrap="nowrap">{{ say('Status Verifikasi') }}</th>
                                    <th data-name="action" width="100px">{{say("Aksi")}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
