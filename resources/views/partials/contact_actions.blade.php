<a href="#"
   data-url="{{ route('contacts.edit', $contact->id) }}"
   class="btn btn-sm btn-primary"
   title="Edit contact"
    id="edit-contact">
    <i class="glyphicon glyphicon-edit"></i>
</a>

<form action="{{ route('contacts.destroy', $contact->id) }}" method="post">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button type="submit" class="btn btn-sm btn-danger" title="Delete contact">
        <i class="glyphicon glyphicon-minus"></i>
    </button>
</form>