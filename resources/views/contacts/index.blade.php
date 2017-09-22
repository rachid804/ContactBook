@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        My contacts
                        <button class="btn btn-primary btn-sm pull-right" data-toggle="modal"
                                data-target="#contactModal">Add contact
                        </button>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered" id="contacts-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal')
@endsection

@push('scripts')
<script>
    $(function () {
        $('#contacts-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('contacts.search') !!}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'surname', name: 'surname'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush