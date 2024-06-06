<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Models\Task;

use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Test\Auth\TestSignUpController;

use function PHPUnit\Framework\isJson;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/task', function (Request $request) {
    //送られて来たデータを抽出
    $jsonData = array_keys($request->all())[0];

    //デコードを行う
    $data = json_decode($jsonData);

    $formattedData = [];

    //Validate::makeに渡せる形に整形
    foreach($data as $key=>$value) {
        $formattedData[$key] = $value;
    }

    $validator = Validator::make($formattedData, [  // $dataをバリデーターに渡す
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        Log::error("Add task failed.");
        return response()->json([
            'error' => $validator->errors()
        ], 400);
    }

    $task = new Task;
    $task->name = $formattedData['name'];  // $dataから'name'を取得
    //DBに保存
    $task->save();
    // Clear the cache
    Cache::flush();

    return response()->json([
        'id' => $formattedData['id'],
        'name' => $task->name,
    ], 200);
});

//.httpでのテスト用
Route::post('/task/test', function (Request $request) {
    $validator = Validator::make($request->all(), [  // $dataをバリデーターに渡す
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        Log::error("Add task failed.");
        return response()->json([
            'error' => $validator->errors()
        ], 400);
    }

    $task = new Task;
    $task->name = $request->name;  // $dataから'name'を取得
    //DBに保存
    $task->save();
    // Clear the cache
    Cache::flush();

    return response()->json([
        'id' => $request->id,
        'name' => $task->name,
    ], 200);
});

Route::post('/sign-up', 'App\Http\Controllers\Auth\SignUpController@signUp');
Route::post('/complete-auth', 'App\Http\Controllers\Auth\CompleteAuthController@checkAuthCode');
Route::post('/sign-in', 'App\Http\Controllers\Auth\SignInController@signIn');
Route::post('/auth-remember-token', 'App\Http\Controllers\Auth\AuthRememberTokenController@authRememberToken');
Route::post('/question', 'App\Http\Controllers\Question\QuestionController@qustionController');
Route::post('/dungeon', 'App\Http\Controllers\Dungeon\DungeonController@getDungeonData');
