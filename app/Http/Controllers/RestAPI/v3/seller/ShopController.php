<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSeen;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    public function vacation_add(Request $request){
        $seller = $request->seller;

        $shop = Shop::where('seller_id',$seller->id)->first();
        $shop->vacation_status = $request->vacation_status;
        $shop->vacation_start_date = $request->vacation_start_date;
        $shop->vacation_end_date = $request->vacation_end_date;
        $shop->vacation_note = $request->vacation_note;
        $shop->save();

        return response()->json(['status' => true], 200);
    }

    public function temporary_close(Request $request){
        $seller = $request->seller;

        $shop = Shop::where('seller_id',$seller->id)->first();
        $shop->temporary_close = $request->status;
        $shop->save();

        Cache::clear();
        return response()->json(['status' => true], 200);
    }


    public function notification_index(Request $request){

        $seller = $request->seller;

        $notification_data = Notification::whereBetween('created_at', [$seller->created_at, Carbon::now()])->where('sent_to', 'seller');

        $notification = $notification_data->with('notificationSeenBy')
                            ->select('id', 'title', 'description', 'image', 'created_at')
                            ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $notification->map(function($data){
            $data['notification_seen_status'] = $data->notificationSeenBy == null ? 0 : 1;
            unset($data->notificationSeenBy);
        });

        return [
            'total_size' => $notification->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'new_notification' => $notification_data->whereDoesntHave('notificationSeenBy')->count(),
            'notification' => $notification->items()
        ];
    }

    public function seller_notification_view(Request $request){

        $seller = $request->seller;

        NotificationSeen::updateOrInsert(['seller_id' => $seller->id, 'notification_id' => $request->id],[
            'created_at' => Carbon::now(),
        ]);

        $notification_count = Notification::whereBetween('created_at', [$seller->created_at, Carbon::now()])
                                ->where('sent_to', 'seller')
                                ->whereDoesntHave('notificationSeenBy')
                                ->count();

        return [
            'notification_count' => $notification_count,
        ];
    }


}
