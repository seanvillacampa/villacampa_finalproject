<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = [
        'name', 'tag_number', 'breed_id', 'sex',
        'weight', 'birthdate', 'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'weight'    => 'decimal:2',
    ];

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function healthLogs()
    {
        return $this->hasMany(AnimalHealthLog::class);
    }

    public function feedLogs()
    {
        return $this->hasMany(AnimalFeedLog::class);
    }

    public function getAgeAttribute(): string
    {
        if (!$this->birthdate) return 'Unknown';
        $diff = $this->birthdate->diff(now());
        if ($diff->y > 0) return $diff->y . ' yr' . ($diff->y > 1 ? 's' : '');
        if ($diff->m > 0) return $diff->m . ' mo' . ($diff->m > 1 ? 's' : '');
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
    }
}