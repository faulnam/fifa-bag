<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Retrieve or initialize persistent cart session token.
     */
    protected function getCartToken(Request $request): string
    {
        $token = $request->session()->get('cart_token');
        if (! $token) {
            $token = (string) Str::uuid();
            $request->session()->put('cart_token', $token);
        }

        return $token;
    }

    /**
     * Display the full cart page.
     */
    public function index(Request $request): View
    {
        $cart = $this->cartService->getCart(Auth::user(), $this->getCartToken($request));
        $summary = $this->cartService->getCartSummary($cart);

        return view('cart.index', compact('cart', 'summary'));
    }

    /**
     * Return JSON data for Alpine cart store and real-time updates.
     */
    public function data(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart(Auth::user(), $this->getCartToken($request));
        $summary = $this->cartService->getCartSummary($cart);

        return response()->json($summary);
    }

    /**
     * Add product variant to cart.
     */
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $cart = $this->cartService->getCart(Auth::user(), $this->getCartToken($request));

        try {
            $this->cartService->addItem($cart, (int) $validated['product_variant_id'], $qty);
            $summary = $this->cartService->getCartSummary($cart);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang.',
                    'cart' => $summary,
                ]);
            }

            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first() ?? 'Gagal menambahkan produk ke keranjang.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }

            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Update quantity of an item in cart.
     */
    public function update(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $cart = $this->cartService->getCart(Auth::user(), $this->getCartToken($request));

        // Ensure user/session owns this cart item
        if ($item->cart_id !== $cart->id) {
            abort(403, 'Akses tidak sah.');
        }

        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $this->cartService->updateItemQty($item, (int) $validated['qty']);
            $summary = $this->cartService->getCartSummary($cart);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil diperbarui.',
                    'cart' => $summary,
                ]);
            }

            return back()->with('success', 'Keranjang berhasil diperbarui.');
        } catch (ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first() ?? 'Gagal memperbarui kuantitas.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }

            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Remove an item from cart.
     */
    public function destroy(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $cart = $this->cartService->getCart(Auth::user(), $this->getCartToken($request));

        if ($item->cart_id !== $cart->id) {
            abort(403, 'Akses tidak sah.');
        }

        $this->cartService->removeItem($item);
        $summary = $this->cartService->getCartSummary($cart);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang.',
                'cart' => $summary,
            ]);
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
