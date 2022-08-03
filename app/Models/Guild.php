<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guild extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'association_id',
        'name',
        'guild_id'
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
    public function guildMember()
    {
        return $this->hasMany(\App\Models\GuildMember::class, 'guild_id')
            ->whereNull('out');
    }
    public function guildWar()
    {
        return $this->hasMany(\App\Models\GuildWar::class, 'guild)id');
    }
    public function starExpedition()
    {
        return $this->hasMany(\App\Models\StarExpedition::class, 'guild)id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function association()
    {
        return $this->belongsTo(\App\Models\Association::class, 'association_id');
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
