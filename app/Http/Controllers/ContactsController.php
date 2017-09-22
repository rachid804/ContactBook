<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Jobs\UnsubscribeContact;
use App\Jobs\UpdateActiveCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\Config;
use Yajra\Datatables\Datatables;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contacts.index');
    }


    /**
     * Store a newly created contact in DB and update Associated ActiveCampaign contact.
     *
     * @param ContactStoreRequest|Request $request
     * @param null $id
     * @return array
     */
    public function store(ContactStoreRequest $request)
    {
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->surname = $request->surname;
        $contact->email = $request->email;
        $contact->phone = $request->phone;

        if ($request->customFields) {
            $contact->custom_fields = $request->customFields;
        }

        $contact->user()->associate($request->user());

        $status = [
            'success' => false,
            'message' => 'Contact could not be saved, please try again'
        ];

        if ($contact->save()) {
            //Queue job to update ActiveCampaign contact
            $this->dispatch(new UpdateActiveCampaign($contact->toArray()));

            $status = [
                'success' => true,
                'message' => 'Contact successfully saved to database.'
            ];
        }

        return $status;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Contact $contact
     * @return Contact
     */
    public function edit(Contact $contact): Contact
    {
        return $contact;
    }

    /**
     * Update the contact.
     *
     * @param ContactUpdateRequest|Request $request
     * @param Contact $contact
     * @return array
     */
    public function update(ContactUpdateRequest $request, Contact $contact)
    {

        $status = [
            'success' => false,
            'message' => 'Something was wrong'
        ];

        if ($contact) {

            if ($contact->update($request->all())) {
                //Queue job to update ActiveCampaign contact
                $this->dispatch(new UpdateActiveCampaign($contact));

                $status = [
                    'success' => true,
                    'message' => 'Contact successfully updated'
                ];
            }

        } else {
            $status = [
                'success' => false,
                'message' => 'Contact could not found'
            ];
        }

        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conditions = ['user_id' => Auth::id(), 'id' => $id];

        $contact = Contact::where($conditions)->first();

        if ($contact) {
            $contact->delete();

            //Unsubscribe the contact
            $this->dispatch(new UnsubscribeContact($contact->toArray()));

            return redirect()
                ->route('contacts.index')
                ->with('success', 'Contact successfully deleted');
        }

        return redirect()
            ->route('contacts.index')
            ->with('error', "The Contact doesn't exist or you don't have the permission to delete it");
    }

    /**
     * Retrieve list of current connected user contacts for datatables
     * @throws \Exception
     */
    public function search()
    {
        $contacts = Contact::query()->where('user_id', Auth::id());

        $data = Datatables::of($contacts)
            ->addColumn('action', function ($contact) {
                return view('partials.contact_actions', compact('contact'));
            })
            ->make(true);

        return $data;
    }
}
