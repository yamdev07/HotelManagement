<?php

namespace App\Repositories\Implementation;

use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function store($request, Customer $customer, Room $room)
    {
        return Transaction::create([
            'user_id' => auth()->user()->id,
            'customer_id' => $customer->id,
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => 'Reservation',
        ]);
    }

    public function getTransaction($request)
    {
        return Transaction::with('user', 'room', 'customer')
            ->where('check_out', '>=', Carbon::now())
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('transactions.id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('customers.name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('check_out', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(20)
            ->appends($request->all());
    }


    public function getTransactionExpired($request)
    {
        return Transaction::with('user', 'room', 'customer')
            ->where('check_out', '<', Carbon::now())
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('transactions.id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('customers.name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('check_out', 'ASC')
            ->paginate(20)
            ->appends($request->all());
    }

}
