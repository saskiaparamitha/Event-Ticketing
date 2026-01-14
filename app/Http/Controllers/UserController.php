<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $events = Event::all();
        $myTickets = TicketOrder::where('user_id', Auth::id())->with('event')->get();
        
        return view('user.dashboard', compact('events', 'myTickets'));
    }
    
    public function bookTicket(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // 1. Validasi apakah kuota masih tersedia
        if ($event->quota <= 0) {
            return back()->with('error', 'Maaf, tiket sudah habis!');
        }

        // 2. Validasi apakah user sudah memesan event ini
        $existingOrder = TicketOrder::where('user_id', Auth::id())
                                   ->where('event_id', $event->id)
                                   ->first();
        if ($existingOrder) {
            return back()->with('error', 'Anda sudah memesan tiket untuk event ini!');
        }

        // 3. Validasi quantity (default 1 jika tidak disediakan)
        $quantity = $request->input('quantity', 1);
        if ($quantity > $event->quota) {
            return back()->with('error', 'Jumlah tiket yang diminta melebihi kuota tersedia!');
        }

        // 4. Simpan pesanan ke tabel ticket_orders
        TicketOrder::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'quantity' => $quantity,
            'order_date' => now(),
            'status' => 'pending'
        ]);

        // 5. Kurangi kuota event secara otomatis
        $event->decrement('quota', $quantity);

        // Jika AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tiket ' . $event->name . ' berhasil dipesan! Menunggu konfirmasi admin.'
            ]);
        }

        return back()->with('success', 'Tiket ' . $event->name . ' berhasil dipesan! Menunggu konfirmasi admin.');
    }
}