<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    // Wyświetlanie listy ticketów
    public function index()
    {
        $tickets = Ticket::with('attachments', 'worker')->latest()->paginate(10);
        return view('ticket.ticket', compact('tickets'));
    }

    // Formularz tworzenia ticketa
    public function create()
    {
        $users = User::all(); // Użytkownicy do przypisania ticketa
        return view('ticket.edit', compact('users'));
    }

    // Tworzenie nowego ticketa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'worker_id' => $validated['assigned_to'] ?? null,
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            $ticket->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket został dodany.');
    }

    // Wyświetlanie ticketa
    public function show($id)
    {
        $ticket = Ticket::with('attachments', 'worker')->findOrFail($id);
        return view('ticket.show', compact('ticket'));
    }

    // Formularz edycji ticketa
    public function edit($id)
    {
        $ticket = Ticket::with('attachments', 'worker')->findOrFail($id);
        $users = User::all();
        return view('ticket.edit', compact('ticket', 'users'));
    }

    // Aktualizacja ticketa
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Przypisywanie użytkownika (admin)
        if (auth()->user()->is_admin && isset($validated['assigned_to'])) {
            $ticket->worker_id = $validated['assigned_to'];
        }

        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('attachment')) {
            foreach ($ticket->attachments as $attachment) {
                Storage::delete('public/' . $attachment->file_path);
                $attachment->delete();
            }

            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            $ticket->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket został zaktualizowany.');
    }

    // Usuwanie ticketa
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        foreach ($ticket->attachments as $attachment) {
            Storage::delete('public/' . $attachment->file_path);
            $attachment->delete();
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket został usunięty.');
    }

    // Dodawanie załącznika do istniejącego ticketa
    public function addAttachment(Request $request, $ticketId)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $ticket = Ticket::findOrFail($ticketId);
        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public');

        $ticket->attachments()->create([
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'Załącznik został dodany.');
    }

    // Usuwanie załącznika
    public function destroyAttachment($ticketId, $attachmentId)
    {
        $attachment = Attachment::findOrFail($attachmentId);
        Storage::delete('public/' . $attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Załącznik został usunięty.');
    }
}
