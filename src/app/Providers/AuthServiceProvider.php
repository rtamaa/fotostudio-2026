<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\StudioBlock;
use App\Policies\BookingPolicy;
use App\Policies\PackagePolicy;
use App\Policies\StudioBlockPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Booking::class => BookingPolicy::class,
        Package::class => PackagePolicy::class,
        StudioBlock::class => StudioBlockPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}