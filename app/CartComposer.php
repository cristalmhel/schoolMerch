<?php

namespace App;

use Illuminate\View\View;
use App\Http\Controllers\CartController; 

class CartComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $count = CartController::getCartItemCount();
        $view->with('cartItemCount', $count);
    }
}