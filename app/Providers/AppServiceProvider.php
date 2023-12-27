<?php

namespace App\Providers;

use App\Repositories\Booking\BookingRepository;
use App\Repositories\Booking\Interface\BookingRepositoryInterface;
use App\Repositories\Employer\Employer\EmployerRepository;
use App\Repositories\Employer\Employer\EmployerSalonServiceRepository;
use App\Repositories\Employer\Employer\Interface\EmployerRepositoryInterface;
use App\Repositories\Employer\Employer\Interface\EmployerSalonServiceRepositoryInterface;
use App\Repositories\Employer\Leave\EmployerLeaveRepository;
use App\Repositories\Employer\Leave\Interface\EmployerLeaveRepositoryInterface;
use App\Repositories\Employer\WorkingDay\EmployerWorkingDayRepository;
use App\Repositories\Employer\WorkingDay\Interface\EmployerWorkingDayRepositoryInterface;
use App\Repositories\Review\EmployerReviewRepository;
use App\Repositories\Review\Interface\EmployerReviewRepositoryInterface;
use App\Repositories\Review\Interface\SalonReviewRepositoryInterface;
use App\Repositories\Review\SalonReviewRepository;
use App\Repositories\Salon\Interface\SalonSubServiceRepositoryInterface;
use App\Repositories\Salon\SalonServiceBannerImageRepository;
use App\Repositories\Salon\Interface\BannerImageRepositoryInterface;
use App\Repositories\Salon\Interface\PlaceOfferRepositoryInterface;
use App\Repositories\Salon\Interface\SalonRepositoryInterface;
use App\Repositories\Salon\Interface\SalonServiceRepositoryInterface;
use App\Repositories\Salon\Interface\SalonTypeRepositoryInterface;
use App\Repositories\Salon\Interface\ServiceRepositoryInterface;
use App\Repositories\Salon\PlaceOfferRepository;
use App\Repositories\Salon\SalonRepository;
use App\Repositories\Salon\SalonServiceRepository;
use App\Repositories\Salon\SalonSubServiceRepository;
use App\Repositories\Salon\SalonTypeRepository;
use App\Repositories\Salon\ServiceRepository;
use App\Repositories\Setting\Interface\SettingRepositoryInterface;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\User\Interface\UserLevelRepositoryInterface;
use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Repositories\User\UserLevelRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserLevelRepositoryInterface::class, UserLevelRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SalonRepositoryInterface::class, SalonRepository::class);
        $this->app->bind(SalonServiceRepositoryInterface::class, SalonServiceRepository::class);
        $this->app->bind(EmployerRepositoryInterface::class, EmployerRepository::class);
        $this->app->bind(EmployerLeaveRepositoryInterface::class, EmployerLeaveRepository::class);
        $this->app->bind(EmployerWorkingDayRepositoryInterface::class, EmployerWorkingDayRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(SalonTypeRepositoryInterface::class, SalonTypeRepository::class);
        $this->app->bind(EmployerSalonServiceRepositoryInterface::class, EmployerSalonServiceRepository::class);
        $this->app->bind(PlaceOfferRepositoryInterface::class, PlaceOfferRepository::class);
        $this->app->bind(SalonSubServiceRepositoryInterface::class, SalonSubServiceRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(EmployerReviewRepositoryInterface::class, EmployerReviewRepository::class);
        $this->app->bind(SalonReviewRepositoryInterface::class, SalonReviewRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
