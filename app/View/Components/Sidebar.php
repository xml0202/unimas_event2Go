<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = Category::query()
                    ->select('categories.category_name', DB::raw('count(*) as total'))
                    ->groupBy([
                        'categories.category_name'
                    ])
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get();
        return view('components.sidebar', compact('categories'));
    }
}
