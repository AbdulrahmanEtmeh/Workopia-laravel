<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected $fillable = [
        'title',
        'description',
        'salary',
        'tags',
        'job_type',
        'remote',
        'requirements',
        'benefits',
        'address',
        'city',
        'state',
        'zipcode',
        'contact_email',
        'contact_phone',
        'company_name',
        'company_description',
        'company_logo',
        'company_website',
        'user_id',
    ];

    //Relation to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getJobTypeLabelAttribute(): string
    {
        return match ($this->job_type) {
            'full_time' => 'Full-Time',
            'part_time' => 'Part-Time',
            'contract' => 'Contract',
            'temporary' => 'Temporary',
            'internship' => 'Internship',
            'volunteer' => 'Volunteer',
            'on_call' => 'On-Call',
            default => $this->job_type,
        };
    }
}
