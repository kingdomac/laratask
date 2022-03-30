<?php

namespace App\Models;

use App\Models\Issue;
use App\Enums\LabelEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'due_date', 'agent_id', 'description', 'budget', 'website', 'created_by'];

    protected $casts = [
        'budget' => 'integer',
        'created_at' => 'datetime:Y-m-d',
        'due_date' => 'date:Y-m-d'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agent()
    {
        return  $this->belongsTo(User::class, 'agent_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function inDanger(): Attribute
    {
        return new Attribute(
            // project is in danger phase if  date is before 2 days of duedate
            get: fn () => $this->due_date <= now()->addDays(2) && $this->due_date >=  now(),
        );
    }


    public function scopeConditions($query, $userId = null)
    {
        return
            $query->with(['agent', 'creator'])
            ->withCount(['issues' => function ($query) use ($userId) {
                $query->whereNull('parent_id');
                $query->byUserId($userId);
                $query->orWhere(function ($q) {
                    $q->whereIn('id', function ($q) {
                        $q->select('parent_id')->from('issues');
                        $q->where('user_id', auth()->user()->id);
                    });
                });
            }])

            ->withCount(['issues as verified_issues_count' => function ($query) use ($userId) {
                $query->where('status_id', '=', StatusEnum::VERIFIED->value);
                $query->byUserId($userId);
            }])

            ->withCount(['issues as active_issues_count' => function ($query)  use ($userId) {
                $query->where(function ($q) {
                    $q->where('status_id', '!=', StatusEnum::CANCELED->value)
                        ->orWhereNull('status_id');
                });
                $query->where('label_id', '!=', LabelEnum::STORY->value);
                $query->byUserId($userId);
            }])

            ->withCount(['issues as count_new_issues' => function ($query)  use ($userId) {
                $query->byUserId($userId)->where('is_new', true);
            }])

            ->when(auth()->user()->isAdmin || $userId, function ($query) use ($userId) {
                $query->whereHas('issues', function ($q) use ($userId) {
                    $q->byUserId($userId);
                }, '>', 0);
            })
            ->when(request('keyword'), function ($q) {
                $q->where('name', 'like', '%' . request('keyword') . '%');
                $q->orWhereHas('agent', function ($q) {
                    $q->where('name', 'like', request('keyword') . '%');
                });
            });
    }
}
