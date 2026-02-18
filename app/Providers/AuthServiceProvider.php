<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Submission;
use App\Policies\SubmissionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Submission::class => SubmissionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}