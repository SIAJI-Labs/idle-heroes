<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Period extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'length',
        'timezone_offset'
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
    public function guildWar()
    {
        return $this->hasMany(\App\Models\GuildWar::class, 'period_id');
    }
    public function starExpedition()
    {
        return $this->hasMany(\App\Models\StarExpedition::class, 'period_id');
    }

    /**
     * Foreign Key Relation
     * 
     * @return model
     */

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

        // Listen to Saving Event
        static::saving(function ($model) {
            if (empty($model->timezone_offset)) {
                $model->timezone_offset = env('APP_TIMEZONE_OFFSET');
            }

            // Convert Datetime to UTC
            $raw = date('Y-m-d H:i:s', strtotime($model->datetime));
            $timezone = ($model->timezone_offset ?? env('APP_TIMEZONE_OFFSET', 0));
            // Convert to UTC
            $utc = convertToUtc($raw, $timezone);
            $datetime = date('Y-m-d H:i:00', strtotime($utc));
            $date = date('Y-m-d', strtotime($utc));
            $time = date('H:i:00', strtotime($utc));

            // Set Date
            $model->datetime = $datetime;
            $model->date = $date;
            $model->time = $time;
        });
    }
}
