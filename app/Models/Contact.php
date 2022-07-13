<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'active',
    ];

    /**
     * @return HasMany
     */
    public function contact_replies(): HasMany
    {
        return $this->hasMany(ReplyContact::class);
    }
}
