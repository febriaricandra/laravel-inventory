<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TransactionProductCollection;
use App\Http\Resources\TransactionProductResource;
use App\Models\TransactionProduct;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Reports",
 *     description="API Endpoints for Transaction Reports"
 * )
 */
class ReportController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/reports",
     *     summary="Get transaction reports with optional filters",
     *     tags={"Reports"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date for filtering transactions (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date for filtering transactions (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Transaction type (in/out)",
     *         required=false,
     *
     *         @OA\Schema(type="string", enum={"in", "out"})
     *     ),
     *
     *     @OA\Parameter(
     *         name="supplier_id",
     *         in="query",
     *         description="Filter by supplier ID",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="product_id",
     *         in="query",
     *         description="Filter by product ID",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reports retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="data", type="array",
     *
     *                     @OA\Items(
     *
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="type", type="string"),
     *                         @OA\Property(property="quantity", type="integer"),
     *                         @OA\Property(property="product", type="object"),
     *                         @OA\Property(property="supplier", type="object"),
     *                         @OA\Property(property="user", type="object"),
     *                         @OA\Property(property="created_at", type="string", format="datetime"),
     *                         @OA\Property(property="updated_at", type="string", format="datetime")
     *                     )
     *                 ),
     *                 @OA\Property(property="pagination", type="object")
     *             ),
     *             @OA\Property(property="message", type="string", example="Reports retrieved successfully")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = TransactionProduct::with(['product', 'supplier', 'user']);

        // Apply date filters
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Apply type filter
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Apply supplier filter
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply product filter
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Get paginated results
        $transactions = $query->paginate(10);

        return $this->sendResponse(
            new TransactionProductCollection($transactions),
            'Reports retrieved successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/{id}",
     *     summary="Get specific transaction report details",
     *     tags={"Reports"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Report retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="quantity", type="integer"),
     *                 @OA\Property(property="product", type="object"),
     *                 @OA\Property(property="supplier", type="object"),
     *                 @OA\Property(property="user", type="object"),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             ),
     *             @OA\Property(property="message", type="string", example="Report retrieved successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Report not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        $transaction = TransactionProduct::with(['product', 'supplier', 'user'])->findOrFail($id);

        return $this->sendResponse(
            new TransactionProductResource($transaction),
            'Report retrieved successfully'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/reports/summary",
     *     summary="Get transaction summary statistics",
     *     tags={"Reports"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date for filtering (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date for filtering (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Summary retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total_in", type="integer"),
     *                 @OA\Property(property="total_out", type="integer"),
     *                 @OA\Property(property="total_products", type="integer"),
     *                 @OA\Property(property="total_suppliers", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string", example="Summary retrieved successfully")
     *         )
     *     )
     * )
     */
    public function summary(Request $request)
    {
        $query = TransactionProduct::query();

        // Apply date filters
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $summary = [
            'total_in' => (clone $query)->where('type', 'in')->sum('quantity'),
            'total_out' => (clone $query)->where('type', 'out')->sum('quantity'),
            'total_products' => (clone $query)->distinct('product_id')->count('product_id'),
            'total_suppliers' => (clone $query)->distinct('supplier_id')->count('supplier_id'),
        ];

        return $this->sendResponse($summary, 'Summary retrieved successfully');
    }
}
