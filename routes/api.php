<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\UserInfoController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\ExternalController;
use App\Http\Controllers\API\ClientCartController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/events/{event}/upload-pdf', [EventController::class, 'uploadPdf']);
Route::get('/events/{event}/pdfs', [EventController::class, 'getPdfs']);
Route::post('postdata', [ExternalController::class, 'postRequest']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
Route::get("profile", [AuthController::class, "profile"]);
Route::get("refresh-token", [AuthController::class, "refreshToken"]);
Route::get("logout_unimas", [AuthController::class, "logout_unimas"]);
Route::post("login_unimas", [AuthController::class, "login_unimas"]);

Route::post('postdata', [ExternalController::class, 'postRequest']);

// Route::group(["middleware" => ['auth:sanctum', 'verified']], function (){
//     Route::post('logout', [AuthController::class, 'logout']);
    
//     Route::get('events', [EventController::class, 'index']);
    
//     Route::get('/event/{eventId}/officers', [EventController::class, 'getEventOfficers']);
//     Route::get('/event/{eventId}/vips', [EventController::class, 'getEventVIPs']);
// });

Route::middleware('auth:api')->group(function () {
    // Route::get('events', [EventController::class, 'index']);
    Route::get('events/{id}', [EventController::class, 'show']);
    Route::post('/update_registration_token', [EventController::class, 'storeFcmToken']);
    // Add other protected routes here...
});

Route::get('/event_attendance', [EventController::class, 'getAttendanceByDate']);
Route::post('/attendance_event', [EventController::class, 'attendanceEvent']);
Route::post('/search_user', [EventController::class, 'search_user']);
Route::post('/list_officer', [EventController::class, 'listOfficer']);
Route::post('/list_pending_officer', [EventController::class, 'listPendingOfficer']);
Route::post('/remove_officer', [EventController::class, 'remove_officer']);
Route::post('/get_officer_events', [EventController::class, 'getOfficerEvents']);
Route::post('/attendance_event_details', [EventController::class, 'getEventAttendanceDetails']);

Route::get('events', [EventController::class, 'index']);
Route::post('events', [EventController::class, 'store']);
Route::put('events/{id}', [EventController::class, 'update']);
Route::get('events-pending-approval', [EventController::class, 'pendingApproval']);
Route::post('/event/{eventId}/approve-or-reject', [EventController::class, 'approveOrReject']);
// Route::get('events/{id}', [EventController::class, 'show']);
Route::post('/get_attandance_date', [EventController::class, 'getEventAttendanceDate']);

Route::delete('events/{id}', [EventController::class, 'destroy']);

// Route::get('/users', [UserController::class, 'getUsersWithUserRole']);
Route::get('events/{event}/comments', [EventController::class, 'get_current_event_comments']);
Route::get('/attendees/{attendeeId}/events', [EventController::class, 'get_joined_events']);
Route::get('get_user_bookmarked_events/{user_id}', [EventController::class, 'getUserBookmarkedEvents']);
Route::get('get_events_by_category/{category_id}', [EventController::class, 'getEventsbyCategory']);
Route::get('get_all_ongoing_events', [EventController::class, 'getOngoingEvents']);
Route::get('get_six_ongoing_events', [EventController::class, 'getSixOngoingEvents']);
Route::get('get_all_ongoing_events/{year}/{month}', [EventController::class, 'getOngoingEventsByYear']);
Route::get('/get_all_upcoming_events', [EventController::class, 'getUpcomingEvents']);
Route::get('get_six_upcoming_events', [EventController::class, 'getSixUpcomingEvents']);
Route::get('/get_all_upcoming_events/{year}/{month}', [EventController::class, 'getUpcomingEventsByYear']);
Route::get('/my_past_events/{userId}', [EventController::class, 'getUserPastEvents']);
Route::get('/my_joined_events/{userId}', [EventController::class, 'getUserJoinedEvents']);
Route::get('/get_event_attendees/{eventId}', [EventController::class, 'getEventAttendees']);
Route::get('/user/notifications/{userId}', [EventController::class, 'getUserNotifications']);
Route::get('/users/points', [EventController::class, 'getAllUsersPoints']);
Route::get('/users/points/{userId}', [EventController::class, 'getUserPoints']);
Route::get('/users/highest-points', [EventController::class, 'getHighestPointsUsers']);
Route::get('/users/info/{userId}', [EventController::class, 'getUserInfo']);
Route::post('/bookmarks', [BookmarkController::class, 'addBookmark']);
Route::delete('/bookmarks', [BookmarkController::class, 'removeBookmark']);
Route::post('/likes', [LikeController::class, 'like']);
Route::delete('/likes', [LikeController::class, 'unlike']);
Route::post('/users/events/join', [EventController::class, 'joinEvent']);
Route::post('/users/events/unjoin', [EventController::class, 'unjoinEvent']);
Route::post('/invitations/send', [EventController::class, 'sendInvitation']);
Route::post('/become-officer', [EventController::class, 'becomeOfficer']);
Route::post('/become-vip', [EventController::class, 'becomeVIP']);
Route::get('/agency_users/{agencyId}/events', [EventController::class, 'getUserAgencyEvents']);
Route::get('/agency_users/{userId}/events/ongoing', [EventController::class, 'getUserAgencyOngoingEvents']);
Route::get('/agency_users/{userId}/events/completed', [EventController::class, 'getUserAgencyCompletedEvents']);
Route::get('/admin/{adminId}/events', [EventController::class, 'getAdminEvents']);

Route::get('/get_event_point', [EventController::class, 'getEventPoint']);

Route::get('comments', [CommentController::class, 'index']);
Route::post('comments', [CommentController::class, 'store']);
Route::get('comments/{comment}', [CommentController::class, 'show']);
Route::put('comments/{comment}', [CommentController::class, 'update']);
Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

Route::get('bookmarks', [BookmarkController::class, 'index']);
Route::post('bookmarks', [BookmarkController::class, 'store']);
Route::get('bookmarks/{bookmark}', [BookmarkController::class, 'show']);
Route::put('bookmarks/{bookmark}', [BookmarkController::class, 'update']);
Route::delete('bookmarks/{bookmark}', [BookmarkController::class, 'destroy']);
// Route::post('bookmarks', [BookmarkController::class, 'bookmark']);
// Route::delete('bookmarks', [BookmarkController::class, 'unbookmark']);

Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::put('categories/{category}', [CategoryController::class, 'update']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

Route::get('news', [NewsController::class, 'index']);
Route::post('news', [NewsController::class, 'store']);
Route::put('news/{news}', [NewsController::class, 'update']);
Route::get('news/{news}', [NewsController::class, 'show']);
Route::delete('news/{news}', [NewsController::class, 'destroy']);
Route::get('event_news/{news}', [NewsController::class, 'getEventNews']);

Route::get('notifications', [NotificationController::class, 'index']);
Route::post('notifications', [NotificationController::class, 'store']);
Route::put('notifications/{notification}', [NotificationController::class, 'update']);
Route::get('notifications/{notification}', [NotificationController::class, 'show']);
Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);

Route::get('faqs', [FaqController::class, 'index']);
Route::post('faqs', [FaqController::class, 'store']);
Route::put('faqs/{faq}', [FaqController::class, 'update']);
Route::get('faqs/{faq}', [FaqController::class, 'show']);
Route::delete('faqs/{faq}', [FaqController::class, 'destroy']);

Route::get('user_infos', [UserInfoController::class, 'index']);
Route::post('user_infos', [UserInfoController::class, 'store']);
Route::put('user_infos/{user_info}', [UserInfoController::class, 'update']);
Route::get('user_infos/{user_info}', [UserInfoController::class, 'show']);
Route::delete('user_infos/{user_info}', [UserInfoController::class, 'destroy']);

Route::get('/get_feedback_list', [EventController::class, 'getFeedbackList']);

Route::post('/register_event_team', [EventController::class, 'register_event_team']);
Route::delete('/unregister_event_team', [EventController::class, 'unregister_event_team']);
Route::post('/get_event_point', [EventController::class, 'getEventPoint']);
Route::post('/assign_points', [EventController::class, 'assign_points']);
Route::get('/events/{event_id}/placement-summary', [EventController::class, 'getEventPlacementSummary']);
Route::get('/events/{event_id}/teams', [EventController::class, 'getEventTeams']);

Route::apiResource('products', ProductController::class);

Route::get('cart', [ClientCartController::class, 'index']);
Route::post('cart/add/{product}', [ClientCartController::class, 'add']);
Route::patch('cart/update', [ClientCartController::class, 'update']);
Route::delete('cart/remove/{product}', [ClientCartController::class, 'remove']);
Route::get('cart/summary', [ClientCartController::class, 'summary']);

Route::get('/send-fcm-notification', [EventController::class, 'sendNotificationUsingFCMHttpV1']);

Route::post('/increase-view', [EventController::class, 'increaseView']);
Route::get('/event/{eventId}/vips', [EventController::class, 'getEventVIPs']);
