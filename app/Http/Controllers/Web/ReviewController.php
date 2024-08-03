<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Traits\FileManagerTrait;
use App\Utils\ImageManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function React\Promise\all;

class ReviewController extends Controller
{
    use FileManagerTrait;

    public function __construct(
        private readonly ReviewRepositoryInterface $reviewRepo,
        private readonly OrderRepositoryInterface  $orderRepo,
    )
    {}

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'rating' => 'required',
            'comment' => 'required',
        ], [
            'rating.required' => translate('please_rate_the_quality') . '!',
            'comment.required' => translate('The_comment_is_required') . '!',
        ]);

        $imageArray = [];
        $review = $this->reviewRepo->getFirstWhere(params: ['customer_id' => auth('customer')->id(), 'id' => $request['review_id']]);
        if ($review && $review['attachment']) {
            foreach ($review['attachment'] as $image) {
                $imageArray[] = $image;
            }
        }

        if ($request->has('fileUpload')) {
            foreach ($request->file('fileUpload') as $image) {
                $imageArray[] = [
                    'file_name' => $this->upload(dir: 'review/', format: 'webp', image: $image),
                    'storage' => getWebConfig(name: 'storage_connection_type') ?? 'public',
                ];
            }
        }
        if ($request['review_id']) {
            $review_id = $request['review_id'];
        } else {
            $oldReview = $this->reviewRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['order_id' => $request['order_id']]);
            if (count($oldReview) > 0) {
                $review_id = $oldReview[0]['order_id'] . (count($oldReview) + 1);
            } else {
                $review_id = $request['order_id'] . '1';
            }
        }

        $dataArray = [
            'id' => $review_id,
            'customer_id' => auth('customer')->id(),
            'product_id' => $request['product_id'],
            'order_id' => $request['order_id'],
            'comment' => $request['comment'],
            'rating' => $request['rating'],
            'attachment' => $request->has('fileUpload') ? $imageArray : ($review->attachment ?? null),
            'updated_at' => now(),
            'created_at' => $review->created_at ?? now()
        ];

        if ($request['review_id']) {
            $this->reviewRepo->update(id: $request['review_id'], data: $dataArray);
        } else {
            $this->reviewRepo->add(data: $dataArray);
        }

        Toastr::success(translate('successfully_added_review'));
        return redirect()->back();
    }

    public function addDeliveryManReview(Request $request): RedirectResponse
    {
        $order = $this->orderRepo->getFirstWhere(params: ['id' => $request['order_id'], 'customer_id' => auth('customer')->id(), 'payment_status' => 'paid']);
        if (!isset($order->delivery_man_id)) {
            Toastr::error(translate('Invalid_review'));
            return redirect('/');
        }

        if ($request['rating'] == 0) {
            Toastr::error(translate('please_select_ratting'));
            return back();
        }

        $review = $this->reviewRepo->getFirstWhere(params: [
            'delivery_man_id' => $order['delivery_man_id'],
            'customer_id' => auth('customer')->id(),
            'order_id' => $request['order_id'],
        ]);

        $dataArray = [
            'customer_id' => auth('customer')->id(),
            'delivery_man_id' => $order['delivery_man_id'],
            'order_id' => $request['order_id'],
            'comment' => $request['comment'],
            'rating' => $request['rating'],
            'updated_at' => now(),
        ];

        if (!$review) {
            $dataArray['created_at'] = now();
        }

        $this->reviewRepo->updateOrInsert(params: [
            'delivery_man_id' => $order['delivery_man_id'],
            'customer_id' => auth('customer')->id(),
            'order_id' => $request['order_id']
        ], data: $dataArray
        );

        Toastr::success(translate('successfully_added_review'));
        return back();
    }

    public function deleteReviewImage(Request $request): JsonResponse
    {
        $review = Review::find($request['id']);
        $array = [];

        foreach ($review['attachment'] as $image) {
            $imageName = isset($image['file_name']) ? $image['file_name'] : $image;
            if ($imageName != $request['name']) {
                $array[] = $image;
            }else{
                $this->delete('review/' . $request['name']);
            }
        }

        $review->attachment = $array;
        $review->save();
        return response()->json(['message' => translate('review_image_removed_successfully')], 200);
    }

}
