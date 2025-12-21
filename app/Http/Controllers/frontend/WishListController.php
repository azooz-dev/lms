<?php

declare(strict_types=1);

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Services\WishListService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishListController extends Controller
{
    public function __construct(
        private readonly WishListService $wishListService
    ) {}

    public function store_wishList(Request $request): JsonResponse
    {
        try {
            if (! Auth::check()) {
                return response()->json(['error' => 'At first login your account.'], 401);
            }

            $result = $this->wishListService->toggleWishList(
                Auth::user()->id,
                (int) $request->id
            );

            return response()->json(['success' => $result['message']], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function wishList_view(): View
    {
        return view('frontend.dashboard.wishlist.all_wishList');
    }

    public function all_wishList(string $id): JsonResponse
    {
        try {
            $courses = $this->wishListService->getUserWishListCourses((int) $id);

            return response()->json([
                'courses' => $courses,
                'courses_count' => $courses->count(),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete_wishlist(string $id, string $course): JsonResponse
    {
        try {
            $result = $this->wishListService->removeFromWishList((int) $id, (int) $course);

            if ($result['success']) {
                return response()->json(['success' => $result['message']], 200);
            }

            return response()->json(['error' => $result['message']], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
