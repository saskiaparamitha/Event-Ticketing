<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; // Pastikan Model Event sudah dibuat sebelumnya
use App\Models\TicketOrder;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Ambil semua data event dari database untuk ditampilkan di tabel
        $events = Event::all();
        $ticketOrders = TicketOrder::with(['user', 'event'])->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('events', 'ticketOrders'));
    }

    public function storeEvent(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'location' => 'required|string',
            'quota' => 'required|integer',
        ]);

        // 2. Simpan ke database
        Event::create([
            'name' => $request->name,
            'price' => $request->price,
            'date' => $request->date,
            'location' => $request->location,
            'quota' => $request->quota,
        ]);

        // 3. Redirect kembali ke dashboard
        return redirect()->route('admin.dashboard')->with('success', 'Event baru berhasil ditambahkan!');
    }

    public function editEvent(Event $event)
    {
        return view('admin.edit-event', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'location' => 'required|string',
            'quota' => 'required|integer',
        ]);

        // 2. Update data event
        $event->update([
            'name' => $request->name,
            'price' => $request->price,
            'date' => $request->date,
            'location' => $request->location,
            'quota' => $request->quota,
        ]);

        // 3. Redirect kembali ke dashboard
        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil diperbarui!');
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event berhasil dihapus!');
    }

    public function approveTicketOrder(Request $request, TicketOrder $ticketOrder)
    {
        $ticketOrder->update(['status' => 'approved']);
        
        if ($request->ajax()) {
            return response()->json(['success' => 'Pemesanan tiket berhasil disetujui!']);
        }
        
        return redirect()->route('admin.dashboard')->with('success', 'Pemesanan tiket berhasil disetujui!');
    }

    public function rejectTicketOrder(Request $request, TicketOrder $ticketOrder)
    {
        // Kembalikan kuota jika ditolak
        $ticketOrder->event->increment('quota', $ticketOrder->quantity);
        $ticketOrder->update(['status' => 'rejected']);
        
        if ($request->ajax()) {
            return response()->json(['success' => 'Pemesanan tiket berhasil ditolak!']);
        }
        
        return redirect()->route('admin.dashboard')->with('success', 'Pemesanan tiket berhasil ditolak!');
    }
}