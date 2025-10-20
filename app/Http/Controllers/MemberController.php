<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Address;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::with('address')->get();
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $addressId = null;
        if ($request->filled('street') || $request->filled('city')) {
            $address = Address::create([
                'street' => $request->street,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
            ]);
            $addressId = $address->id;
        }

        Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'address_id' => $addressId,
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Thành viên đã được thêm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        $member->load('address');
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $member->load('address');
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'new_password' => 'nullable|string|min:8|confirmed',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        if ($request->filled('street') || $request->filled('city')) {
            if ($member->address) {
                $member->address->update([
                    'street' => $request->street,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                    'country' => $request->country,
                ]);
            } else {
                $address = Address::create([
                    'street' => $request->street,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                    'country' => $request->country,
                ]);
                $member->address_id = $address->id;
            }
        }

        $member->name = $request->name;
        $member->email = $request->email;
        $member->phone = $request->phone;

        if ($request->filled('new_password')) {
            $member->password = $request->new_password;
        }

        $member->save();

        return redirect()->route('members.index')
            ->with('success', 'Thông tin thành viên đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Thành viên đã được xóa thành công.');
    }

    public function profile()
    {
        $user = auth()->user();
        
        if (!$user || !$user->isMember()) {
            return redirect()->route('dashboard')->with('error', 'Chỉ thành viên mới có thể truy cập trang này.');
        }

        $currentLendings = $user->getCurrentLendings();
        $activeReservations = $user->getActiveReservations();
        $libraryCard = $user->libraryCard;
        $address = $user->address;

        return view('members.profile', compact('user', 'currentLendings', 'activeReservations', 'libraryCard', 'address'));
    }

    public function lendingHistory()
    {
        $user = auth()->user();
        
        if (!$user || !$user->isMember()) {
            return redirect()->route('dashboard')->with('error', 'Chỉ thành viên mới có thể truy cập trang này.');
        }

        $lendingHistory = $user->getLendingHistory();
        $reservations = $user->reservations()->with(['bookItem.book.author'])->orderBy('reservation_date', 'desc')->get();

        return view('members.lending-history', compact('user', 'lendingHistory', 'reservations'));
    }
}