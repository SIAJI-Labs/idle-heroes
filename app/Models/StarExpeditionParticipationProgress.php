<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StarExpeditionParticipationProgress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'star_expedition_participation_id',
        'key',
        'value',
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
     * Accessor - Getter
     */
    public function getValueAttribute($value)
    {
        return in_array($this->key, ['map_1', 'map_2', 'map_3', 'map_4', 'map_5', 'map_6', 'map_7']) ? (date("Y-m-d H:i:s", strtotime($value)) !== date("Y-m-d H:i:s", strtotime('-')) ? $value : 0) : $value;
    }

    /**
     * Primary Key Relation
     * 
     * @return model
     */

    /**
     * Foreign Key Relation
     * 
     * @return model
     */
    public function starExpeditionParticipation()
    {
        return $this->belongsTo(\App\Models\StarExpeditionParticipation::class, 'star_expedition_participation_id');
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

        // Listen to Saving Event
        static::saving(function ($model) {
            if(in_array($model->key, ['map_1', 'map_2', 'map_3', 'map_4', 'map_5', 'map_6', 'map_7'])){
                // Validate if value is date

                // Convert UTC to Server Timezone
                if(date("Y-m-d H:i:s", strtotime($model->value)) !== date("Y-m-d H:i:s", strtotime('-'))){
                    $model->value = date('Y-m-d H:i:s', strtotime(convertToUtc($model->value, env('APP_TIMEZONE_OFFSET'), false)));
                }
                if (empty($model->timezone_offset)) {
                    $model->timezone_offset = env('APP_TIMEZONE_OFFSET');
                }
            }
        });
    }
}
