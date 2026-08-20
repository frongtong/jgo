<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocabularyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id', 'japanese_word', 'reading', 'meaning_th',
        'example_japanese', 'example_reading', 'example_thai',
        'word_audio_url', 'example_audio_url', 'image_url', 'status', 'sort_order',
    ];

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class);
    }
}
