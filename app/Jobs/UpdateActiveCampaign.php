<?php

namespace App\Jobs;

use App\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateActiveCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Contact
     */
    public $contact;

    /**
     * Create a new job instance.
     *
     * @param Contact $contact
     */
    public function __construct(Contact $contact)
    {
        $this->$contact = $contact;
    }

    /**
     * Execute the job.
     * @return void
     * @throws \Exception
     */
    public function handle()
    {

        $ac = app('ActiveCampaign');

        $list_id = 1;
        $acContact = array(
            'email' => $contact->email,
            'first_name' => $this->contact->name,
            'last_name' => $this->contact->surname,
            "p[{$list_id}]"      => $list_id,
            "status[{$list_id}]" => 1,
        );

        //Update or Add contact to ActiveCampaign
        $contact_sync = $ac->api("contact/sync", $acContact);

        //Will cause Job to fail after the number of set attempts
        if (!(int)$contact_sync->success) {
            throw new \Exception();
        }
    }
}
