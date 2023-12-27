<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Employer\EmployerController;
use App\Http\Controllers\API\Employer\EmployerSalonServiceController;
use App\Http\Controllers\API\Employer\Leave\EmployerLeaveController;
use App\Http\Controllers\API\Employer\WorkingDay\EmployerWorkingDayController;
use App\Http\Controllers\API\Salon\PlaceOffer\PlaceOfferController;
use App\Http\Controllers\API\Salon\SalonController;
use App\Http\Controllers\API\Salon\SalonService\SalonServiceController;
use App\Http\Controllers\API\Salon\SalonSubService\SalonSubServiceController;
use App\Http\Controllers\API\Salon\SalonType\SalonTypeController;
use App\Http\Controllers\API\Salon\Service\ServiceController;
use App\Http\Controllers\API\Setting\SettingController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Booking\BookingController;
use App\Http\Controllers\API\Review\SalonReviewController;
use App\Http\Controllers\API\Review\EmployerReviewController;
use App\Http\Controllers\API\Route53\Route53Controller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/confirm-email/{user_id}/{key}', [AuthController::class, 'confirmMail']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'super_admin','middleware' =>['super_admin']], function () {
            Route::post('/salon/get-all/pending', [SalonController::class, 'getAllPendingSalon']);
            Route::post('/salon/status-change', [SalonController::class, 'changeSalonStatus']);

            Route::put('/setting/change', [SettingController::class, 'changeSetting']);
            Route::get('/setting/get', [SettingController::class, 'index']);
        });

        Route::post('/create-sub-domain', [Route53Controller::class, 'createSubdomain']);

        Route::post('/user/get-all', [UserController::class, 'index']);
        Route::post('/user/update', [UserController::class, 'update']);

        Route::post('/booking/request', [BookingController::class, 'store']);
        Route::post('/booking/get-all', [BookingController::class, 'index']);
        Route::post('/booking/status-change', [BookingController::class, 'changeBookingStatus']);
        Route::post('/booking/get-employer-available-time', [BookingController::class, 'getEmployerSubSalonServicesAvailableTime']);
        Route::post('/booking/employer/get-all', [BookingController::class, 'getEmployerBookings']);

        Route::post('/salon/create', [SalonController::class, 'store']);
        Route::post('/salon/update', [SalonController::class, 'updateSalon']);
        Route::post('/salon/get-all', [SalonController::class, 'index']);
        Route::post('/salon/filter-name', [SalonController::class, 'findByName']);
        Route::get('/salon/{id}', [SalonController::class, 'findById']);
        Route::delete('/salon/banner-image/{id}', [SalonController::class, 'deleteBannerImage']);

//        Route::delete('/salon/delete/{id}', [SalonController::class, 'destroy']);

        Route::post('/salon/salon-service/create', [SalonServiceController::class, 'store']);
        Route::post('/salon/salon-service/update', [SalonServiceController::class, 'update']);
        Route::post('/salon/salon-service/get-all', [SalonServiceController::class, 'index']);
        Route::get('/salon/salon-service/{id}', [SalonServiceController::class, 'findById']);
        Route::get('/salon/salon-service/salon-id/{id}', [SalonServiceController::class, 'findBySalonId']);
        Route::delete('/salon/salon-service/banner-image/{id}', [SalonServiceController::class, 'deleteSalonServiceBannerImage']);
        Route::delete('/salon/salon-service/{id}', [SalonServiceController::class, 'deleteSalonService']);


        Route::post('/salon/salon-sub-service/create', [SalonSubServiceController::class, 'store']);
        Route::post('/salon/salon-sub-service/update', [SalonSubServiceController::class, 'update']);
        Route::get('/salon/salon-sub-service/{id}', [SalonSubServiceController::class, 'findById']);
        Route::delete('/salon/salon-sub-service/{id}', [SalonSubServiceController::class, 'deleteSalonSubService']);
        Route::get('/salon/salon-sub-service/salon-service-id/{id}', [SalonSubServiceController::class, 'findBySalonServiceId']);
        Route::post('/salon/salon-sub-service/filter-name', [SalonSubServiceController::class, 'findByName']);

        //placeOffer
        Route::post('/salon/place-offer/create', [PlaceOfferController::class, 'store']);
        Route::post('/salon/place-offer/get-all', [PlaceOfferController::class, 'index']);
        Route::get('/salon/place-offer/{id}', [PlaceOfferController::class, 'findById']);

//        //service
        Route::post('/service/create', [ServiceController::class, 'store']);
        Route::post('/service/get-all', [ServiceController::class, 'index']);
        Route::get('/service/{id}', [ServiceController::class, 'findById']);
//        salon types
        Route::post('/salon-type/create', [SalonTypeController::class, 'store']);
        Route::post('/salon-type/get-all', [SalonTypeController::class, 'index']);
        Route::get('/salon-type/{id}', [SalonTypeController::class, 'findById']);

        Route::post('/employer/create', [EmployerController::class, 'store']);
        Route::put('/employer/update', [EmployerController::class, 'update']);
        Route::post('/employer/get-all', [EmployerController::class, 'index']);
        Route::post('/employer/activation', [EmployerController::class, 'activation']);
        Route::get('/employer/all-employers-by-salon_id/{salon_id}', [EmployerController::class, 'getAllEmployersBySalonId']);
        Route::get('/employer/{id}', [EmployerController::class, 'findById']);
        Route::get('/employer/user/{user_id}', [EmployerController::class, 'getEmployerfindByUser']);
        Route::delete('/employer/{id}', [EmployerController::class, 'deleteEmployer']);
        Route::post('/employer/filter-name', [EmployerController::class, 'findByName']);

        Route::post('/employer/leave/create', [EmployerLeaveController::class, 'store']);
        Route::post('/employer/leave/get-all', [EmployerLeaveController::class, 'index']);
        Route::post('/employer/leave/manage', [EmployerLeaveController::class, 'leaveManage']);
        Route::get('/employer/leave/all-salonId/{salonId}', [EmployerLeaveController::class, 'getAllLeavesBySalonId']);
        Route::get('/employer/leave/all-employerId/{employerId}', [EmployerLeaveController::class, 'getAllLeavesByEmployerId']);
        Route::get('/employer/leave/{id}', [EmployerLeaveController::class, 'findById']);
        Route::delete('/employer/leave/{id}', [EmployerLeaveController::class, 'delete']);

        Route::post('/employer/working-day/create', [EmployerWorkingDayController::class, 'store']);
        Route::post('/employer/working-day/get-all', [EmployerWorkingDayController::class, 'index']);
        Route::get('/employer/working-day/{id}', [EmployerWorkingDayController::class, 'findById']);

        Route::post('/employer/salon-service/create', [EmployerSalonServiceController::class, 'store']);
        Route::post('/employer/salon-service/get-all', [EmployerSalonServiceController::class, 'index']);
        Route::get('/employer/salon-service/{id}', [EmployerSalonServiceController::class, 'findById']);
        Route::delete('/employer/salon-service/{employer_salon_service_id}', [EmployerSalonServiceController::class, 'deleteEmployerFromEmployerSalonService']);

        Route::get('/employer/all-employer-by-salon-sub-service-id/{salonSubServiceId}', [EmployerSalonServiceController::class, 'allEmployerBySalonSubServiceId']);
        Route::get('/employer/all-salon-service-by-employerId/{employerId}', [EmployerSalonServiceController::class, 'allSalonSubServiceByEmployerId']);


        Route::post('/review/salon/create', [SalonReviewController::class, 'store']);
        Route::put('/review/salon/update', [SalonReviewController::class, 'update']);
        Route::delete('/review/salon/{id}', [SalonReviewController::class, 'deleteSalonReview']);

        Route::post('/review/employer/create', [EmployerReviewController::class, 'store']);
        Route::put('/review/employer/update', [EmployerReviewController::class, 'update']);
        Route::delete('/review/employer/{id}', [EmployerReviewController::class, 'deleteEmployerReview']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });

});
