<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{
    public function index()
    {
        $product = Product::paginate(9);
        // $product = Product::all();
        return view('home.userpage',compact('product'));
    }
    public function redirect()
    {
        $usertype = Auth::user()->usertype;
        $product = Product::paginate(9);
        if($usertype == '1')
        {
            return view('admin.home',compact('product'));
        }
        else 
        {
            return view('home.userpage',compact('product'));
        }
    }
    public function product_details($id)
    {
        $products = Product::find($id);
        return view('home.product_details',compact('products'));
    }
    public function add_cart(Request $request, $id)
    {
        if(Auth::id())
        {
            $user = Auth::user();
            $product = Product::find($id);

            $cart = new Cart;
            $cart->name = $user->name;
            $cart->email = $user->email;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->user_id = $user->id;

            $cart->product_title = $product->title;
            if($product->discount_price != null)
            {
                $cart->price = $product->discount_price * $request->Quantity;
            }
            else{
                $cart->price = $product->price * $request->Quantity;
            }
            $cart->image = $product->image;
            $cart->Product_id = $product->id;
            $cart->quantity = $request->Quantity;
            $cart->save();
            
            return redirect()->back();
        }
        else
        {
            return redirect('login');
        }
    }
}
