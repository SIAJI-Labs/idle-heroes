<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GuildMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guild_id',
        'player_id',
        'join',
        'out'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 
    ];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Primary Key Relation
     * 
     * @return model
     */
    public function guildWarParticipation()
    {
        return $this->hasMany(\App\Models\GuildWarParticipation::class, 'guild_member_id');
    }
    public function starExpeditionParticipation()
    {
        return $this->hasMany(\App\Models\StarExpeditionParticipation::class, 'guild_member_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function guild()
    {
        return $this->belongsTo(\App\Models\Guild::class, 'guild_id');
    }
    public function player()
    {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Listen to Create Event
        static::creating(function ($model) {
            // Always generate UUID on Data Create
            $model->{'uuid'} = (string) Str::uuid();
        });
    }
}
