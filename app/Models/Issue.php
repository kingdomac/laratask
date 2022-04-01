<?php

namespace App\Models;

use App\Enums\LabelEnum;
use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Issue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'details',
        'story_point',
        'value_point',
        'project_id',
        'label_id',
        'priority_id',
        'status_id',
        'story_point',
        'value_point',
        'assigned_by',
        'parent_id',
        'created_by',
        'updated_by',
        'is_new',
        'user_id',
        'order',
        'sprint_id'
    ];

    protected $casts = [
        //'label_id' => LabelEnum::class,
        //'priority_id' => PriorityEnum::class,
        'is_true' => 'boolean',
    ];

    public function isStory(): Attribute
    {
        return new Attribute(
            get: fn () => $this->label_id == LabelEnum::STORY->value,
        );
    }

    public function isCanceled(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status_id == StatusEnum::CANCELED->value,
        );
    }

    public function isCompleted(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status_id == StatusEnum::COMPLETED->value,
        );
    }

    public function isVerified(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status_id == StatusEnum::VERIFIED->value,
        );
    }


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Issue::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Issue::class, 'parent_id');
    }

    public function childrenCount()
    {
        return $this->hasMany(Issue::class, 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'issue_user')
            ->withPivot('time_spent', 'completion_perc', 'status_id')
            ->withTimestamps();
    }

    public function history()
    {
        return $this->belongsToMany(User::class, 'issue_user')

            ->withPivot('time_spent', 'completion_perc', 'status_id')
            ->withTimestamps()->leftJoin('statuses', 'issue_user.status_id', '=', 'statuses.id')
            // ->selectRaw('users.*, statuses.name as status_name,  statuses.color as status_color')
            ->orderBy('issue_user.created_at', 'desc');
    }


    public function scopeByUserId($query, $userId = NULL)
    {
        return $query->when(
            auth()->user()->isAdmin,
            fn ($q) => $q->where('user_id', auth()->user()->id),
            // Else
            fn ($q) => $query->when(
                auth()->user()->isSuperAdmin && $userId,
                fn ($q) => $q->where('user_id', $userId)
            )

        );
    }
}
