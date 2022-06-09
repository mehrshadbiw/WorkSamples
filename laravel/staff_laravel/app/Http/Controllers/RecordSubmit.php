<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use Illuminate\Database\Console\DbCommand;
use Illuminate\Support\Facades\Auth;

class RecordSubmit extends Controller
{
    public function record(Request $request)
    {

        $date = $request->date;
        $action = $request->action;
        $user = auth()->user();
        $data = $request->data;

        if (!$user) {
            return response()->json([
                "status" => "failed",
                "error" => "User is not logined!"
            ]);
        } else {
            $record = WorkTime::where('date', $date)
                ->where('user_id', $user->id)->get();
            if ($action === "start" && !count($record)) {
                $enter = $data["enter_time"];
                $vacation = $data["vacation"];
                $worktime = new WorkTime();
                $worktime->date = $date;
                $worktime->enter_time = $enter;
                $worktime->user_id = $user->id;
                $worktime->vacation = $vacation;
                $worktime->save();
                return response()->json([
                    'status' => 'ok',
                    'record' =>  $vacation ? 'vacation ' : 'enter ' . 'recorded',
                    'res' => $enter
                ]);
            } else if ($action === "start" && !!count($record)) {
                return response()->json([
                    'status' => 'error',
                    'result' => 'The record is available'
                ]);
            }
            if ($action === "end" && !!count($record)) {
                $exit = $data["exit_time"];
                // $id = $user->id;
                // $exit_time = WorkTime::find($id);
                // $exit_time->exit_time = $exit;
                // $exit_time->save();
                WorkTime::where('user_id', $user->id)
                    ->where('date', $date)
                    ->update(['exit_time' => $exit]);
                return response()->json([
                    'status' => 'ok',
                    'result' => 'User exit_time updated.'
                ]);
            } else if ($action === "end" && !count($record)) {
                return response()->json([
                    'status' => 'error',
                    'result' => 'End recording failed'
                ]);
            }
            if ($action === "log" && !!count($record)) {
                $time_log = $data['time_log'];
                foreach ($time_log as $log) {
                    return $log;
                }
                Worktime::where('user_id', $user->id)
                    ->where('date', $date)
                    ->update(['time_log' => $log]);
                return response()->json([
                    "status" => "ok",
                    'result' => 'Time_log recorded'
                ]);
            } else if ($action === "log" && !count($record)) {
                return response()->json([
                    'status' => 'error',
                    'result' => 'Record is not available'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'result' => 'Nothing is recorded',
            ]);
        }
    }
}
