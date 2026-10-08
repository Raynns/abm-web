<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class CartService
{
    public const SESSION_KEY = 'abm_cart';
    public const MAX_QUANTITY = 999;
    public const MAX_PRODUCTS = 50;

    /** @return array<int,int> Only product IDs and quantities; prices always come from DB. */
    public function quantities(Request $request): array
    {
        $saved = $request->session()->get(self::SESSION_KEY, []);
        if (!is_array($saved)) {
            return [];
        }

        $quantities = [];
        foreach ($saved as $id => $quantity) {
            if (count($quantities) >= self::MAX_PRODUCTS) {
                break;
            }
            if (!ctype_digit((string) $id) || (int) $id < 1 ||
                filter_var($quantity, FILTER_VALIDATE_INT) === false ||
                (int) $quantity < 1 || (int) $quantity > self::MAX_QUANTITY) {
                continue;
            }
            $quantities[(int) $id] = (int) $quantity;
        }

        return $quantities;
    }

    public function count(Request $request): int
    {
        return array_sum($this->quantities($request));
    }

    /**
     * @return array{lines:array<int,array{product:?Product,product_id:int,quantity:int,subtotal:int,issue:?string}>,subtotal:int,has_issues:bool}
     */
    public function snapshot(Request $request): array
    {
        $quantities = $this->quantities($request);
        $products = Product::whereIn('id', array_keys($quantities))->with('category')->get()->keyBy('id');
        $lines = [];
        $subtotal = 0;
        $hasIssues = false;

        foreach ($quantities as $id => $quantity) {
            $product = $products->get($id);
            $issue = null;
            if (!$product) {
                $issue = 'Produk sudah tidak tersedia.';
            } elseif (!$product->is_active) {
                $issue = 'Produk sedang tidak dijual.';
            } elseif ($quantity > $product->stock) {
                $issue = 'Stok tersedia hanya '.$product->stock.'.';
            }

            $lineTotal = $product ? (int) $product->price * $quantity : 0;
            $subtotal += $lineTotal;
            $hasIssues = $hasIssues || $issue !== null;
            $lines[] = [
                'product' => $product,
                'product_id' => $id,
                'quantity' => $quantity,
                'subtotal' => $lineTotal,
                'issue' => $issue,
            ];
        }

        return compact('lines', 'subtotal', 'hasIssues');
    }
}
