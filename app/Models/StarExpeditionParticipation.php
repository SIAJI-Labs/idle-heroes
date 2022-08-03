<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StarExpeditionParticipation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'star_expedition_id',
        'guild_member_id'
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
    public function starExpeditionParticipationProgress()
    {
        return $this->hasMany(\App\Models\StarExpeditionParticipationProgress::class, 'star_expedition_participation_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function starExpedition()
    {
        return $this->belongsTo(\App\Models\StarExpedition::class, 'star_expedition_id');
    }
    public function guildMember()
    {
        return $this->belongsTo(\App\Models\GuildMember::class, 'guild_member_id');
    }

    /**
     * Scope
     *
     * Run specific function
     */
    public function scopeGetProgress($query, $key = null, $timezone = null)
    {
        if(!empty($timezone)){
            return $this->starExpeditionParticipationProgress->where('key', $key)
                ->first() ? $this->starExpeditionParticipationProgress->where('key', $key)
                ->first()->timezone_offset : [];
        }

        return $this->starExpeditionParticipationProgress->where('key', $key)
            ->first() ? $this->starExpeditionParticipationProgress->where('key', $key)
            ->first()->value : [];
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
