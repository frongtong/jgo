<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomerRequest;
use App\Models\Backend\DATAContact;
class CustomerRequestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = CustomerRequest::find($this->id);

        $email = $this->subject('หัวข้อ ' . $data->type)
            ->view('front-end.pages.email_customer_request', ['data' => $data])
            ->to($data->email);

            if (!empty($data->email_type)) {
                $companyEmails = explode(',',$data->email_type);   
          
                $email->cc($companyEmails);
            }    
       

        return $email;
    }
}
