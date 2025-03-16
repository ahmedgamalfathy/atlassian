<?php
namespace App\Filters\Reservation;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ReservationFilterDate implements Filter {
    public function __invoke(Builder $query, $value, $property)
    {
        switch ($value) {
            case 'today':
              $query->whereDate('date', Carbon::today());
            case 'weekly':
               $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            case 'monthly':
               $query->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            default:
                 $query;
        }
    }
}
?>
