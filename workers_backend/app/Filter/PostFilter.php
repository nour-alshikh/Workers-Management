<?php

namespace App\Filter;

use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PostFilter
{
    function filter()
    {
        return [
            'price', 'worker.name',
            AllowedFilter::callback('item', function (Builder $query, $value) {
                $query->where('price', 'like', "%{$value}%")
                    ->orWhere('content', 'like', "%{$value}%")
                    ->orWhereHas('worker', function (Builder $query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
            }),
        ];
    }
}
