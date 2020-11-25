<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Models\Compare;
use App\Models\Product;

class CompareController extends Controller
{

    public function compare()
    {
        $this->code_image();
        if (!Session::has('compare')) {
            return view('front.compare');
        }
        $oldCompare = Session::get('compare');
        $compare = new Compare($oldCompare);
        $products = $compare->items;
        return view('front.compare', compact('products')); 
    }

    public function addcompare($id)
    {
        $data[0] = 0;   
        $prod = Product::findOrFail($id);
        $oldCompare = Session::has('compare') ? Session::get('compare') : null;
        $compare = new Compare($oldCompare);
        $compare->add($prod, $prod->id);
        Session::put('compare',$compare);
        if($compare->items[$id]['ck'] == 1)
        {
            $data[0] = 1;
        }
        $data[1] = count($compare->items);      
        return response()->json($data);   
   
    }

    public function removecompare($id)
    {
        $data[0] = 0;
        $oldCompare = Session::has('compare') ? Session::get('compare') : null;
        $compare = new Compare($oldCompare);  
        $compare->removeItem($id);
        $data[1] = count($compare->items);  
        if (count($compare->items) > 0) {
            Session::put('compare', $compare);
            return response()->json($data);  
        } else {
            $data[0] = 1;
            Session::forget('compare');
            return response()->json($data); 
        }     
    }

    public function clearcompare($id)
    {
        Session::forget('compare');
    }
}