@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Contacts
                        <button class="btn btn-primary btn-sm pull-right" data-toggle="modal"
                                data-target="#contactModal">New contact
                        </button>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @if($contacts)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        @foreach($contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->name }}</td>
                                                <td>{{ $contact->email }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-xs btn-default">Edit</a>
                                                    <a href="#" class="btn btn-xs btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    {{ $contacts->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal')
@endsection
