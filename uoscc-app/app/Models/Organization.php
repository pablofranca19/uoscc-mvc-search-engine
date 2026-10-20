<?php

namespace App\Models;

use App\Enums\FocusArea;
use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    /** @use HasFactory<OrganizationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'contact_email',
        'contact_phone',
        'social_media_links',
        'focus_area',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'focus_area' => FocusArea::class,
        ];
    }

    /**
     * Users (supporters) that belong to this organization.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Activities owned by this organization.
     *
     * @return HasMany<Activity, $this>
     */
    public function ownedActivities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Activities this organization collaborates in.
     *
     * @return BelongsToMany<Activity, $this>
     */
    public function collaboratingActivities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }

    /**
     * Spaces owned by this organization.
     *
     * @return HasMany<Space, $this>
     */
    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }
}
