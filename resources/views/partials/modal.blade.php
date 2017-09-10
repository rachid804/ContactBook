<!-- Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">New Contact</h4>
            </div>
            <form id="contactsFormIndex" action="{{ route('contacts.store') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input name="name" type="text" class="form-control" id="name" placeholder="Name">
                    </div>

                    <div class="form-group">
                        <label for="surname">Surename</label>
                        <input name="surname" type="text" class="form-control" id="surname" placeholder="Surname">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input name="email" type="email" class="form-control" id="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input name="phone" type="phone" class="form-control" id="phone" placeholder="Phone">
                    </div>

                    <div class="customFields">
                        <div class="customFieldsLabel">
                            Custom fields
                            <button class="btn btn-primary btn-sm pull-right" title="Add new custom field"
                                    id="newCustomField">+
                            </button>
                        </div>


                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save contact</button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Template for adding new customfield -->
<div class="form-group row line hide" id="customFieldTemplate">
    <div class="col-xs-4 fieldName">
        <input type="text" class="form-control" name="fieldName" placeholder="Field Name"/>
    </div>
    <div class="col-xs-7 fieldValue">
        <input type="text" class="form-control" name="fieldValue" placeholder="Field Value"/>
    </div>
    <div class="col-xs-1 lineAction">
        <button type="button" class="btn btn-default btn-danger btn-block removeLine" title="Remove this custom field">-</button>
    </div>
</div>