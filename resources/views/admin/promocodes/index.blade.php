@extends('adminlte::page')

@section('title', 'Promocodes')

@section('content_header')
    <h1>Promocodes</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Promocodes list</h3>
                    <a href="{{ route('admin.promocodes.create') }}" class="btn btn-primary float-right">+ Add</a>
                    <a href="{{ route('admin.promocode-usages.index') }}"
                       class="btn btn-outline-secondary float-right mr-2">Usages</a>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex flex-wrap align-items-center">
                        <div class="form-inline mr-3 mb-2">
                            <label for="batch" class="mr-2">Batch:</label>
                            <select id="batch" class="select2 filter-select form-control form-control-sm">
                                <option value="">All</option>
                            </select>
                        </div>
                        <div class="form-inline mr-3 mb-2">
                            <label for="statusFilter" class="mr-2">Status:</label>
                            <select id="statusFilter" class="select2 filter-select form-control form-control-sm">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="Paused">Paused</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="Expired">Expired</option>
                                <option value="Exhausted">Exhausted</option>
                            </select>
                        </div>
                        <div class="form-inline mr-3 mb-2">
                            <label for="typeFilter" class="mr-2">Type:</label>
                            <select id="typeFilter" class="select2 filter-select form-control form-control-sm">
                                <option value="">All</option>
                                <option value="Discount">Discount</option>
                                <option value="Free access">Free access</option>
                            </select>
                        </div>
                        <button id="resetFilters" type="button" class="btn btn-sm btn-outline-secondary mb-2">Reset
                        </button>
                    </div>
                    <div class="mb-3">
                        <button id="bulkDelete" class="btn btn-sm btn-danger" disabled>Delete selected</button>
                    </div>
                    <table id="promocodes-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="width: 36px"><input type="checkbox" id="selectAll"></th>
                            <th style="width: 60px">ID</th>
                            <th>Code</th>
                            <th>Prefix</th>
                            <th>Batch</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Usage</th>
                            <th>Valid from</th>
                            <th>Valid to</th>
                            <th>Status</th>
                            <th style="width: 60px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($promocodes as $promocode)
                            <tr>
                                <td><input type="checkbox" class="row-select" value="{{ $promocode->id }}"></td>
                                <td>{{ $promocode->id }}</td>
                                <td><code>{{ $promocode->code }}</code></td>
                                <td><code>{{ $promocode->prefix ?: '-' }}</code></td>
                                <td>{{ $promocode->batch_id ?: '—' }}</td>
                                <td>
                                    @if($promocode->type === 'free_access')
                                        <span class="badge badge-info">Free access</span>
                                    @else
                                        <span class="badge badge-primary">Discount</span>
                                    @endif
                                </td>
                                <td>{{ $promocode->percent_discount }}%</td>
                                <td>
                                    @if($promocode->usage_limit === 0)
                                        {{ $promocode->usage_count }} / ∞
                                    @else
                                        {{ $promocode->usage_count }} / {{ $promocode->usage_limit }}
                                    @endif
                                </td>
                                <td>
                                    @if($promocode->starts_at)
                                        {{ $promocode->starts_at->format('Y-m-d') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($promocode->expires_at)
                                        {{ $promocode->expires_at->format('Y-m-d') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $now = now();
                                        $paused = !$promocode->is_active;
                                        $expired = $promocode->expires_at && $promocode->expires_at->lt($now);
                                        $scheduled = $promocode->starts_at && $promocode->starts_at->gt($now);
                                        $exhausted = ($promocode->usage_limit ?? 0) > 0 && ($promocode->usage_count ?? 0) >= $promocode->usage_limit;
                                    @endphp
                                    @if($paused)
                                        <span class="badge badge-secondary">Paused</span>
                                    @elseif($expired)
                                        <span class="badge badge-dark">Expired</span>
                                    @elseif($exhausted)
                                        <span class="badge badge-warning">Exhausted</span>
                                    @elseif($scheduled)
                                        <span class="badge badge-info">Scheduled</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.promocodes.edit', $promocode) }}"
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.promocode-usages.index', ['promocode' => $promocode->code]) }}"
                                       class="btn btn-sm {{ ($promocode->usage_count ?? 0) > 0 ? 'btn-info' : 'btn-secondary disabled' }}"
                                       title="Usages">
                                        <i class="fas fa-clipboard-list"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal-{{ $promocode->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <div class="modal fade" id="deleteModal-{{ $promocode->id }}" tabindex="-1"
                                         role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this promocode?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.promocodes.destroy', $promocode) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, delete
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <style>
        .filter-select + .select2 .select2-selection {
            height: 38px !important;
        }

        .filter-select + .select2 .select2-selection__rendered {
            line-height: 35px !important;
        }

        .filter-select + .select2 .select2-selection__arrow {
            height: 38px !important;
            top: 0px;
        }

        #batch.filter-select + .select2 {
            min-width: 380px;
        }

        #statusFilter.filter-select + .select2,
        #typeFilter.filter-select + .select2 {
            min-width: 180px;
        }

        .badge-batch {
            font-size: 11px;
            margin-left: 5px;
            display: inline;
        }

        #resetFilters {
            height: 38px;
            display: inline-flex;
            align-items: center;
        }
    </style>
    <script>
        function formatBatch(option) {
            if (!option.id) return option.text;
            var active = $(option.element).data('active');
            if (active && active > 0) {
                return $(
                    '<span>' + option.text + ' <span class="badge badge-success badge-batch">' + active + '</span></span>'
                );
            }
            return option.text;
        }

        $(document).ready(function() {
            var table = $('#promocodes-table').DataTable({
                initComplete: function() {
                    var api = this.api();
                    var batches = {};
                    api.rows().every(function() {
                        var row = this.data();
                        var batchHtml = row[4];
                        var statusHtml = row[10];
                        var batchText = $('<div>').html(batchHtml).text().trim();
                        var statusText = $('<div>').html(statusHtml).text();
                        var statusClean = statusText.replace(/\s+/g, ' ').trim();
                        var isActiveOrScheduled = /\bActive\b/i.test(statusClean) || /\bScheduled\b/i.test(statusClean);
                        if (batchText && batchText !== '—' && isActiveOrScheduled) {
                            batches[batchText] = (batches[batchText] || 0) + 1;
                        }
                    });
                    var $batch = $('#batch');
                    Object.keys(batches).sort().forEach(function(b) {
                        var count = batches[b];
                        var $opt = $('<option/>', { value: b, text: b }).attr('data-active', count);
                        $batch.append($opt);
                    });
                }
            });

            var $batch = $('#batch');
            $batch.select2({
                placeholder: 'Select batch',
                allowClear: true,
                width: 'resolve',
                templateResult: formatBatch,
                templateSelection: formatBatch
            });

            $batch.on('change', function() {
                var val = $(this).val();
                // Batch column index is now 4 (0-based => 4) due to checkbox column
                table.column(4).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
            });

            // Status filter: filter by text in Status column
            $('#statusFilter').select2({ width: 'resolve', minimumResultsForSearch: -1 });
            $('#statusFilter').on('change', function() {
                var val = $(this).val();
                // Status column index is now 10
                table.column(10).search(val ? '^' + val + '$' : '', true, false).draw();
            });

            // Type filter: filter by text in Type column
            $('#typeFilter').select2({ width: 'resolve', minimumResultsForSearch: -1 });
            $('#typeFilter').on('change', function() {
                var val = $(this).val();
                // Type column index is now 5
                table.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
            });

            // Reset all filters
            $('#resetFilters').on('click', function() {
                // Clear selects
                $('#batch').val('').trigger('change');
                $('#statusFilter').val('').trigger('change');
                $('#typeFilter').val('').trigger('change');
                // Clear table searches explicitly
                table.column(4).search('');
                table.column(5).search('');
                table.column(10).search('');
                table.search('');
                table.draw();
            });

            function selectedIds() {
                var ids = [];
                table.rows({ search: 'applied' }).every(function() {
                    var $node = $(this.node());
                    var $checkbox = $node.find('input.row-select');
                    if ($checkbox.prop('checked')) {
                        ids.push(parseInt($checkbox.val(), 10));
                    }
                });
                return ids;
            }

            // Select all toggle
            $('#selectAll').on('change', function() {
                var checked = this.checked;
                $('#promocodes-table tbody input.row-select').prop('checked', checked);
                $('#bulkDelete').prop('disabled', selectedIds().length === 0);
            });

            // Enable/disable bulk delete based on selection
            $('#promocodes-table').on('change', 'input.row-select', function() {
                $('#bulkDelete').prop('disabled', selectedIds().length === 0);
            });

            // Keep selection across pagination redraws
            table.on('draw', function() {
                if ($('#selectAll').prop('checked')) {
                    $('#promocodes-table tbody input.row-select').prop('checked', true);
                }
                $('#bulkDelete').prop('disabled', selectedIds().length === 0);
            });

            // Bulk delete action
            $('#bulkDelete').on('click', function() {
                var ids = selectedIds();
                if (!ids.length) return;
                if (!confirm('Delete selected promocodes?')) return;
                $.ajax({
                    url: '{{ route('admin.promocodes.bulk-destroy') }}',
                    method: 'DELETE',
                    data: { ids: ids, _token: '{{ csrf_token() }}' },
                    success: function() {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error');
                    }
                });
            });
        });
    </script>
@endsection


