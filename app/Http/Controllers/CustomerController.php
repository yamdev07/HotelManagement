<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\ImageRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function index(Request $request)
    {
        $customers = $this->customerRepository->get($request);

        return view('customer.index', ['customers' => $customers]);
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerRepository->store($request);

        return redirect('customer')->with('success', 'Customer '.$customer->name.' created');
    }

    public function show(Customer $customer)
    {
        return view('customer.show', ['customer' => $customer]);
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', ['customer' => $customer]);
    }

    public function update(Customer $customer, StoreCustomerRequest $request)
    {
        $customer->update($request->all());

        return redirect('customer')->with('success', 'customer '.$customer->name.' udpated!');
    }

   public function destroy(Customer $customer, ImageRepositoryInterface $imageRepository)
{
    try {
        // Sauvegarde du nom pour le message
        $customerName = $customer->name;
        
        // Vérifiez et supprimez l'avatar si le user existe
        if ($customer->user) {
            $user = $customer->user;
            $avatar_path = public_path('img/user/' . $user->name . '-' . $user->id);
            
            // Supprimez l'avatar s'il existe
            if (file_exists($avatar_path) && is_dir($avatar_path)) {
                try {
                    $imageRepository->destroy($avatar_path);
                } catch (\Exception $e) {
                    \Log::warning('Could not delete avatar: ' . $e->getMessage());
                }
            }
            
            // Supprimez l'utilisateur
            $user->delete();
        }
        
        // Supprimez le customer
        $customer->delete();
        
        return redirect('customer')->with('success', 'Customer ' . $customerName . ' deleted!');
        
    } catch (\Exception $e) {
        \Log::error('Delete customer error: ' . $e->getMessage());
        
        // Message d'erreur plus détaillé
        $errorDetails = '';
        
        if (str_contains($e->getMessage(), 'foreign key constraint')) {
            $errorDetails = 'This customer has related records (transactions, payments, etc.). Delete those first.';
        } elseif (isset($e->errorInfo[0]) && $e->errorInfo[0] == '23000') {
            $errorDetails = 'This customer has related records in other tables.';
        } else {
            $errorDetails = $e->getMessage();
        }
        
        return redirect('customer')->with('failed', 'Cannot delete customer! ' . $errorDetails);
    }
}
}
