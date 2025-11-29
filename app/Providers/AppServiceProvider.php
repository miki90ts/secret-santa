<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Models\Assignment;
use App\Models\Gift;
use App\Models\Wish;
use App\Models\Comment;
use App\Policies\EventPolicy;
use App\Policies\AssignmentPolicy;
use App\Policies\GiftPolicy;
use App\Policies\WishPolicy;
use App\Policies\CommentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Assignment::class => AssignmentPolicy::class,
        Gift::class => GiftPolicy::class,
        Wish::class => WishPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        JsonResource::withoutWrapping();

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
