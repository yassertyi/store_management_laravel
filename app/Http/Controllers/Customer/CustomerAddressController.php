<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->paginate(10);
        return view('frontend.customers.dashboard.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('frontend.customers.dashboard.addresses.addresse_forms');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'country'    => 'required|string|max:100',
            'city'       => 'required|string|max:100',
            'street'     => 'required|string|max:255',
            'apartment'  => 'nullable|string|max:100',
            'zip_code'   => 'nullable|string|max:20',
        ]);

        Auth::user()->addresses()->create($request->all());

        return redirect()->route('customer.addresses.index')->with('success', 'تم إضافة العنوان بنجاح');
    }

    public function edit(Address $address)
    {
        $this->authorizeAddress($address);
        return view('frontend.customers.dashboard.addresses.addresse_forms', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeAddress($address);

        $request->validate([
            'title'      => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'country'    => 'required|string|max:100',
            'city'       => 'required|string|max:100',
            'street'     => 'required|string|max:255',
            'apartment'  => 'nullable|string|max:100',
            'zip_code'   => 'nullable|string|max:20',
        ]);

        $address->update($request->all());

        return redirect()->route('customer.addresses.index')->with('success', 'تم تحديث العنوان بنجاح');
    }

    public function destroy(Address $address)
    {
        $this->authorizeAddress($address);
        $address->delete();
        return redirect()->route('customer.addresses.index')->with('success', 'تم حذف العنوان');
    }

    private function authorizeAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'غير مسموح بالوصول لهذا العنوان');
        }
    }
}
