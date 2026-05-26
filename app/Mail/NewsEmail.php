<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Backend\News;
use App\Models\Backend\LogEmail_News;
use App\Models\EmailCustomer;
use App\Models\Backend\NewsCategory;
use App\Models\Backend\News_new;
use App\Models\Backend\News_old;


class NewsEmail extends Mailable
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
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'news_new';
    public function build()
    {
        $data = LogEmail_News::find($this->id);
        $users = EmailCustomer::all();

        if ($data->news_type == 0) {
            $news = News_new::find($data->id_news_new);
        } else {
            $news = News_old::find($data->id_news_old);
        }
        if ($news->type_banner == "image") {
            $this->image = public_path($news->video);
        } else {
            $this->image = public_path($news->cover_image);
        }
        return $this->subject('หัวข้อ ' . $news->title_th)
            ->view('front-end.pages.email_news', ['data' => $data, 'news' => $news])
            ->to($data->email_user);
    }
}
