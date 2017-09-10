<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactStoreRequest;
use App\Jobs\UpdateActiveCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $contacts = Contact::where('user_id', Auth::user()->id)->paginate(1);

        return view('contacts.index', compact('contacts'));
    }


    /**
     * Store a newly created contact in DB and update Associated ActiveCampaign contact.
     *
     * @param ContactStoreRequest|Request $request
     * @return array
     */
    public function store(ContactStoreRequest $request)
    {
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->surname = $request->surname;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->custom_fields = $request->customFields;
        $contact->user_id = Auth::user()->id;

        $status = [
            'success'=>false,
            'message'=>'Something was wrong'
        ];

        if($contact->save()){

            //Queue job to update ActiveCampaign contact
            $this->dispatch(new UpdateActiveCampaign($contact));

            $status = [
                'success'=>true,
                'message'=>'Contact successfully saved to database.'
            ];
        }

        return $status;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
