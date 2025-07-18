<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PageController extends Controller
{
    // Trang chủ
    public function index()
    {
        $newProducts = Product::with('category')->latest()->take(8)->get();
        $topSellingProducts = Product::with('category')->orderBy('sold_count', 'desc')->take(8)->get();
        $widgetProducts1 = Product::with('category')->inRandomOrder()->take(3)->get();
        $widgetProducts2 = Product::with('category')->inRandomOrder()->take(3)->get();
        $widgetProducts3 = Product::with('category')->inRandomOrder()->take(3)->get();
        $widgetProducts4 = Product::with('category')->inRandomOrder()->take(3)->get();
        $widgetProducts5 = Product::with('category')->inRandomOrder()->take(3)->get();

        return view('clients.index', compact(
            'newProducts',
            'topSellingProducts',
            'widgetProducts1',
            'widgetProducts2',
            'widgetProducts3',
            'widgetProducts4',
            'widgetProducts5'
        ));
    }

    // Các phương thức chuyển hướng danh mục (đã đổi sang redirect trong web.php,
    // nên các phương thức này trong controller không thực sự cần thiết nữa nếu chỉ là redirect)
    // Tuy nhiên, nếu bạn muốn có logic phức tạp hơn cho từng trang danh mục, có thể giữ lại
    // hoặc chuyển hẳn sang StoreController.
    // Hiện tại, web.php đã dùng Route::redirect, nên các hàm này có thể được xóa
    // nếu không có logic đặc biệt.
    // Nếu bạn muốn dùng Controller để lấy sản phẩm theo danh mục:
    // public function laptop()
    // {
    //     $category = Category::where('slug', 'laptop')->first();
    //     $products = [];
    //     if ($category) {
    //         $products = $category->products()->paginate(12);
    //     }
    //     $categories = Category::withCount('products')->get();
    //     return view('clients.store', compact('products', 'categories'));
    // }

    // Xóa phương thức `product()` này đi:
    // public function product() {
    //     return view('clients.product');
    // }


    // Các phương thức khác của PageController
    public function categories()
    {
        $categories = Category::all();
        return view('clients.categories', compact('categories'));
    }
    public function policy()
    {
        return view('clients.policy');
    }
    public function terms()
    {
        return view('clients.terms');
    }
    public function aboutUs()
    {
        return view('clients.about-us');
    }
    public function orderAndReturn()
    {
        return view('clients.order-and-return');
    }

    public function checkout(Request $request)
    {
        // Nếu có product_id (mua ngay 1 sản phẩm), ưu tiên lấy từ query
        $productId = $request->query('product_id');
        $configuration = $request->query('configuration');
        $color = $request->query('color');
        $size = $request->query('size');
        $storage = $request->query('storage');
        $quantity = $request->query('quantity', 1);
        $selectedProducts = collect();
        $total = 0;
        $cartCount = 0;
        if (auth()->check()) {
            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
            if ($productId) {
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $product->configuration = $configuration;
                    $product->color = $color;
                    $product->size = $size;
                    $product->storage = $storage;
                    $product->quantity = $quantity;
                    $selectedProducts = collect([$product]);
                    $total = $product->price * $quantity;
                }
            } else {
                // Nếu không truyền cart_items, lấy toàn bộ cart của user
                $cartItemIds = collect(explode(',', $request->query('cart_items', '')))->filter();
                if ($cartItemIds->count() === 0) {
                    $selectedProducts = \App\Models\CartItem::with('product')
                        ->where('user_id', auth()->id())
                        ->get();
                    $total = $selectedProducts->sum(function ($item) {
                        return $item->product ? $item->product->price * $item->quantity : 0;
                    });
                } else {
                    $selectedProducts = \App\Models\CartItem::with('product')
                        ->where('user_id', auth()->id())
                        ->whereIn('id', $cartItemIds)
                        ->get();
                    $total = $selectedProducts->sum(function ($item) {
                        return $item->product ? $item->product->price * $item->quantity : 0;
                    });
                }
            }
        }
        return view('clients.checkout', [
            'selectedCartItems' => $selectedProducts,
            'selectedTotal' => $total,
            'cartCount' => $cartCount
        ]);
    }

    public function blank()
    {
        return view('clients.blank');
    }

    public function login()
    {
        return view('clients.login');
    }

    public function register()
    {
        return view('clients.register');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
