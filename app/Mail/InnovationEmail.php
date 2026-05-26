<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Backend\Innovation;
use App\Models\Backend\LogEmail_Innovation;


class InnovationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $image;
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
        $data = LogEmail_Innovation::find($this->id);
        $innovation = Innovation::leftjoin('innovation_category', 'innovation.category', '=', 'innovation_category.id')->find($data->id_innovation);
        $this->image = public_path('upload/innovation/' . $innovation->banner); 


        return $this->subject('หัวข้อ ' . $innovation->title_th)
        ->view('front-end.pages.email_innovation', ['data' => $data, 'innovation' => $innovation , 'image' => $this->image])
        ->to($data->email_user);
    }
}
