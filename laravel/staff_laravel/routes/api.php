<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecordSubmit;
use App\Http\Controllers\ViewController;
use App\Models\DatabaseTest;
use App\Models\test;
use App\Models\WorkTime;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/workTime', [RecordSubmit::class, 'record']);
});




/*
|--------------------------------------------------------------------------
| Test routes
|--------------------------------------------------------------------------
*/

// Route::post('/users/', [UserController::class, 'store']);
// Route::put('/users/{id}', [UserController::class, 'update']);
// Route::post('/users/login', [UserController::class, 'login']);
// Route::get('/show?id=>{id}={number},{name}={fullName}', [UserController::class, 'url']);

// Route::middleware('myauth')->group( function(){
//     Route::get('/users/list', [UserController::class, 'showUsersList']);
//     Route::post('/login', [UserController::class, 'login']);
// });

Route::get('/test', [ViewController::class, 'showView']);

/*
|--------------------------------------------------------------------------
| Database routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\DB;

Route::get('/submit', function() {

    DB::insert('INSERT INTO work_times(date, enter_time, exit_time, user_id) VALUES(?, ?, ?, ?)', ['14001010', '101010', '101112', '1']);

});

Route::get('/read', function() {

    $read = DB::select('SELECT * FROM work_times WHERE user_id = ?', ['1']);
    // foreach($read as $red) {
    //     return $red->enter_time;
    // };
    return $read;

});

Route::get('/update', function(){

    $update = DB::update('UPDATE work_times SET enter_time ="102010" WHERE user_id = ?', [1]);
    return $update;

});

Route::get('/delete', function() {

    $delete = DB::delete('DELETE FROM work_times where id = ?', [2]);
    return $delete;

});

Route::get('/finding', function() {

    $finding = WorkTime::where('id', 1)->get();
    return $finding;

});

Route::get('/findsth', function(){

    // $sth = WorkTime::findOrFail(1);
    // return $sth;

    $sth = WorkTime::where('users_count', '<', 10)->firstOrFail();
    return $sth;

});

/*
|--------------------------------------------------------------------------
| Eloquent routes
|--------------------------------------------------------------------------
*/


Route::get('/create', function() {
    $test = new User;
    $test->name = "mehrshad";
    $test->full_name = "mehrshad mohammadi";
    $test->email = "mehrshadita@gmail.com";
    $test->password = "123456789";
    $test->save();
});

Route::get('/deletee', function() {
    // test::destroy(1);

    $test = test::find(2);
    $test->delete();
});

Route::get('/softdeleting', function() {
    test::destroy(3);
});

Route::get('/readtrash', function() {
    $mehr = test::withTrashed()->where('id', 3)->get();
    return $mehr;
});

Route::get('/restoretrash', function() {
    test::onlyTrashed()->where('id', 3)->restore();
});

Route::get('/forcedelete', function() {
    test::onlyTrashed()->where('id', 3)->forceDelete();
});



/*
|--------------------------------------------------------------------------
| Eloquent relationships
|--------------------------------------------------------------------------
*/

Route::get('/user/{id}/test', function($id) {
    return User::find($id)->test->password;
});

Route::get('/test/{id}/user', function($id) {
    return test::find($id)->user->name;
});

/* one to many relationship */
Route::get('/tests', function() {

    $user = User::find(1);

    foreach($user->tests as $test ){
        echo $test->name . '<br>';
    };

});

/* many to many relation */
Route::get('/user/{id}/role', function($id){

    // $role = User::find($id)->roles()->orderBy('id', 'desc')->get();
    // return $role;

    $role = User::find($id);
    foreach($role->roles as $role){
        return $role->name;
    }

});
