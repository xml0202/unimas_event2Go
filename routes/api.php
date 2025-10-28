<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\UserInfoController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ClientCartController;
use App\Http\Controllers\ExternalController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
Route::post('login_unimas', [AuthController::class, 'login_unimas']);

Route::post('postdata', [ExternalController::class, 'postRequest']);
Route::get('profile', [AuthController::class, 'profile']);
Route::get('refresh-token', [AuthController::class, 'refreshToken']);

Route::get('send-fcm-notification', [EventController::class, 'sendNotificationUsingFCMHttpV1']);

Route::middleware('auth:api')->group(function () {

    Route::get('logout_unimas', [AuthController::class, 'logout_unimas']);
    Route::post('update_registration_token', [EventController::class, 'storeFcmToken']);

    Route::get('events', [EventController::class, 'index']);
    Route::post('events', [EventController::class, 'store']);
    Route::get('events/{id}', [EventController::class, 'show']);
    Route::put('events/{id}', [EventController::class, 'update']);
    Route::delete('events/{id}', [EventController::class, 'destroy']);

    Route::post('events/{event}/upload-pdf', [EventController::class, 'uploadPdf']);
    Route::get('events/{event}/pdfs', [EventController::class, 'getPdfs']);

    Route::get('events-pending-approval', [EventController::class, 'pendingApproval']);
    Route::post('event/{eventId}/approve-or-reject', [EventController::class, 'approveOrReject']);

    Route::get('event_attendance', [EventController::class, 'getAttendanceByDate']);
    Route::post('attendance_event', [EventController::class, 'attendanceEvent']);
    Route::post('attendance_event_details', [EventController::class, 'getEventAttendanceDetails']);
    Route::post('get_attandance_date', [EventController::class, 'getEventAttendanceDate']);
    Route::post('search_user', [EventController::class, 'search_user']);

    Route::get('/event/{eventId}/officers', [EventController::class, 'getEventOfficers']);
    Route::post('list_officer', [EventController::class, 'listOfficer']);
    Route::post('list_pending_officer', [EventController::class, 'listPendingOfficer']);
    Route::post('remove_officer', [EventController::class, 'remove_officer']);
    Route::post('get_officer_events', [EventController::class, 'getOfficerEvents']);
    Route::post('become-officer', [EventController::class, 'becomeOfficer']);
    Route::post('become-vip', [EventController::class, 'becomeVIP']);
    Route::get('event/{eventId}/vips', [EventController::class, 'getEventVIPs']);

    Route::post('users/events/join', [EventController::class, 'joinEvent']);
    Route::post('users/events/unjoin', [EventController::class, 'unjoinEvent']);
    Route::post('invitations/send', [EventController::class, 'sendInvitation']);
    Route::get('get_event_attendees/{eventId}', [EventController::class, 'getEventAttendees']);

    Route::get('get_all_ongoing_events', [EventController::class, 'getOngoingEvents']);
    Route::get('get_six_ongoing_events', [EventController::class, 'getSixOngoingEvents']);
    Route::get('get_all_ongoing_events/{year}/{month}', [EventController::class, 'getOngoingEventsByYear']);

    Route::get('get_all_upcoming_events', [EventController::class, 'getUpcomingEvents']);
    Route::get('get_six_upcoming_events', [EventController::class, 'getSixUpcomingEvents']);
    Route::get('get_all_upcoming_events/{year}/{month}', [EventController::class, 'getUpcomingEventsByYear']);

    Route::get('get_events_by_category/{category_id}', [EventController::class, 'getEventsbyCategory']);
    Route::get('get_user_bookmarked_events/{user_id}', [EventController::class, 'getUserBookmarkedEvents']);

    Route::get('my_past_events/{userId}', [EventController::class, 'getUserPastEvents']);
    Route::get('my_joined_events/{userId}', [EventController::class, 'getUserJoinedEvents']);

    Route::get('agency_users/{agencyId}/events', [EventController::class, 'getUserAgencyEvents']);
    Route::get('agency_users/{userId}/events/ongoing', [EventController::class, 'getUserAgencyOngoingEvents']);
    Route::get('agency_users/{userId}/events/completed', [EventController::class, 'getUserAgencyCompletedEvents']);
    Route::get('admin/{adminId}/events', [EventController::class, 'getAdminEvents']);

    Route::get('events/{event_id}/teams', [EventController::class, 'getEventTeams']);
    Route::get('events/{event_id}/placement-summary', [EventController::class, 'getEventPlacementSummary']);
    Route::post('register_event_team', [EventController::class, 'register_event_team']);
    Route::delete('unregister_event_team', [EventController::class, 'unregister_event_team']);
    Route::get('get_event_point', [EventController::class, 'getEventPoint']);
    Route::post('get_event_point', [EventController::class, 'getEventPoint']);
    Route::post('assign_points', [EventController::class, 'assign_points']);

    Route::get('get_feedback_list', [EventController::class, 'getFeedbackList']);
    Route::post('increase-view', [EventController::class, 'increaseView']);

    Route::get('user/notifications/{userId}', [EventController::class, 'getUserNotifications']);

    Route::apiResource('comments', CommentController::class);
    Route::apiResource('bookmarks', BookmarkController::class);
    // Route::post('bookmarks/add', [BookmarkController::class, 'addBookmark']);
    // Route::delete('bookmarks/remove', [BookmarkController::class, 'removeBookmark']);

    Route::post('likes', [LikeController::class, 'like']);
    Route::delete('likes', [LikeController::class, 'unlike']);

    Route::apiResource('news', NewsController::class);
    Route::get('event_news/{news}', [NewsController::class, 'getEventNews']);
    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('faqs', FaqController::class);
    Route::apiResource('notifications', NotificationController::class);

    Route::apiResource('user_infos', UserInfoController::class);
    Route::get('users/points', [EventController::class, 'getAllUsersPoints']);
    Route::get('users/points/{userId}', [EventController::class, 'getUserPoints']);
    Route::get('users/highest-points', [EventController::class, 'getHighestPointsUsers']);
    Route::get('users/info/{userId}', [EventController::class, 'getUserInfo']);

    Route::apiResource('products', ProductController::class);
    Route::get('cart', [ClientCartController::class, 'index']);
    Route::post('cart/add/{product}', [ClientCartController::class, 'add']);
    Route::patch('cart/update', [ClientCartController::class, 'update']);
    Route::delete('cart/remove/{product}', [ClientCartController::class, 'remove']);
    Route::get('cart/summary', [ClientCartController::class, 'summary']);
});
