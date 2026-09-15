@extends('layouts.app')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
        <style>
            /* ✅ Tree dengan connector lines */
            .tree {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 14px;
            }

            .tree ul {
                padding-left: 30px;
                margin: 0;
                list-style: none;
                position: relative;
            }

            .tree ul ul {
                padding-left: 30px;
            }

            .tree li {
                position: relative;
                padding: 5px 0;
                list-style: none;
            }

            /* ✅ Vertical line connector */
            .tree li::before {
                content: '';
                position: absolute;
                top: 0;
                left: -15px;
                bottom: 20px;
                width: 1px;
                background: #ccc;
            }

            /* ✅ Horizontal line connector */
            .tree li::after {
                content: '';
                position: absolute;
                top: 15px;
                left: -15px;
                width: 25px;
                height: 1px;
                background: #ccc;
            }

            /* ✅ Remove line for last child */
            .tree li:last-child::before {
                height: 20px;
            }

            /* ✅ Remove horizontal line for first level */
            .tree>ul>li::before,
            .tree>ul>li::after {
                display: none;
            }

            /* ✅ Node item styling */
            .tree .node-item {
                display: inline-block;
                padding: 5px 15px;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s;
                position: relative;
            }

            .tree .node-item:hover {
                background: #f0f0f0;
            }

            .tree .node-item .toggle-icon {
                margin-right: 10px;
                font-weight: bold;
                color: #007bff;
                cursor: pointer;
            }

            .tree .node-item .toggle-icon:hover {
                color: #0056b3;
            }

            .tree .node-item .badge {
                margin-left: 10px;
            }

            .tree .node-item .account-code {
                font-weight: bold;
                color: #666;
                margin-right: 10px;
            }

            .tree .node-item .account-name {
                color: #333;
            }

            /* ✅ Node type styling */
            .tree .node-header .account-name {
                font-weight: bold;
                color: #1a73e8;
            }

            .tree .node-detail .account-name {
                font-weight: normal;
                color: #333;
            }

            /* ✅ Expand/collapse animation */
            .tree .collapsed {
                display: none;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chart of Accounts (Tree View)</h3>
            </div>
            <div class="card-body">
                <div id="account-tree"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
        <script>
            $(document).ready(function() {
                // Ambil data dari server
                $.ajax({
                    url: "{{ url('account/tree') }}",
                    method: 'GET',
                    success: function(data) {
                        $('#account-tree').jstree({
                            core: {
                                data: data,
                                themes: {
                                    name: 'default',
                                    dots: true, // ✅ Tampilkan dots
                                    icons: true,
                                }
                            },
                            plugins: ['wholerow', 'types', 'sort'],
                            types: {
                                'header': {
                                    icon: 'fas fa-folder-open text-warning',
                                    li_class: 'font-weight-bold'
                                },
                                'detail': {
                                    icon: 'fas fa-file-invoice text-primary'
                                }
                            },
                            sort: function(a, b) {
                                // Sorting berdasarkan kode akun
                                var aCode = this.get_node(a).original.code;
                                var bCode = this.get_node(b).original.code;
                                return aCode.localeCompare(bCode);
                            }
                        });

                        // Event saat node dipilih
                        $('#account-tree').on('select_node.jstree', function(e, data) {
                            console.log('Selected:', data.node.id);
                            var accountId = data.node.id;
                            // Redirect ke detail atau edit
                            // window.location.href = '/admin/accounts/' + accountId;
                        });

                        // Event saat node di-double-click
                        $('#account-tree').on('dblclick.jstree', '.jstree-anchor', function() {
                            var nodeId = $(this).closest('li').attr('id');
                            // Bisa untuk edit
                            alert('Edit akun ID: ' + nodeId);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error loading tree data:', xhr);
                        alert('Gagal memuat data tree');
                    }
                });
            });
        </script>
    @endpush
@endsection
