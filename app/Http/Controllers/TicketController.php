<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function store(Request $request)
    {
   
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

    
        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
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

    public function show($id)
    {
       
        $ticket = Ticket::with('attachments')->findOrFail($id);

        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        // Pobieranie ticketa z załącznikami
        $ticket = Ticket::with('attachments')->findOrFail($id);
        // Pobieranie wszystkich użytkowników aby admin mógł przypisać osobę do ticketa
        $users = User::all();

        return view('tickets.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, $id)
    {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'assigned_to' => 'nullable|exists:users,id', 
        ]);

        $ticket = Ticket::findOrFail($id);

        if (auth()->user()->is_admin && isset($validated['assigned_to'])) {
            $ticket->assigned_to = $validated['assigned_to'];
        }

        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            
            $ticket->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket został zaktualizowany.');
    }

    // Metoda do usuwania załączników
    public function destroyAttachment($ticketId, $attachmentId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $attachment = Attachment::findOrFail($attachmentId);

        Storage::delete('public/' . $attachment->file_path);

        $attachment->delete();

        return back()->with('success', 'Załącznik został usunięty.');
    }
}
