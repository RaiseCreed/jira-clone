<?php

namespace App\Http\Controllers;


use App\Models\Ticket;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\TicketComment;

class TicketController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function changePhase($id, Request $request){
        $parameterNames = array_keys($request->all());
        $statusID = $parameterNames[1];
        $ticket = Ticket::findOrFail($id);
        $ticket->ticket_status_id = $statusID;
        $ticket->save();

        $ticket->comments()->create([
            'comment' => 'Zmiana statusu na: ' . $ticket->status->name,
            'date' => now(),
            'author_id' => auth()->id(),
        ]);

        $user = $ticket->owner;
        if($user) 
            Mail::to($user->email)->send(new \App\Mail\NewPhaseTicketMail($ticket));

        return redirect()->route('tickets.show', $id);
    }

    public function assignWorker($id, Request $request){
        $ticket = Ticket::findOrFail($id);
        $ticket->worker_id = $request->selectedWorker;
        $ticket->save();

        // Wysyłamy maila do pracownika
        $worker = User::findOrFail($request->selectedWorker);
        if($worker) 
            Mail::to($worker->email)->send(new \App\Mail\NewAssignedTicketMail($ticket));

        return redirect()->route('tickets.show', $id);
    }

    public function deleteComment(TicketComment $comment)
    {
        $comment->delete();
        return redirect()->route('tickets.show', $comment->ticket_id);
    }

    public function addComment($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $ticket->comments()->create([
            'comment' => $validated['comment'],
            'date' => now(),
            'author_id' => auth()->id(),
            'ticket_id' => $ticket->id
        ]);

        return redirect()->route('tickets.show', $ticket->id);
    }
    
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        $comments = $ticket->comments()->orderBy('created_at', 'desc')->get();
        return view('tickets.show', compact('ticket', 'comments'));
    }

    public function create()
    {
        $categories = \App\Models\TicketCategory::all();
        $priorities = \App\Models\TicketPriority::all();
        $statuses = \App\Models\TicketStatus::all();

        return view('tickets.create', compact('categories', 'priorities', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'deadline' => 'required|date|after:now',
            //'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048'
        ]);

        $ticket = \App\Models\Ticket::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'ticket_category_id' => $validated['ticket_category_id'],
            'ticket_priority_id' => $validated['ticket_priority_id'],
            'ticket_status_id' => 1,
            'deadline' => $validated['deadline'],
            'date' => now(),
            'owner_id' => auth()->id(),
            'worker_id' => null,
        ]);
      
        // if ($request->hasFile('attachment')) {
        //     $file = $request->file('attachment');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $path = $file->storeAs('uploads', $filename, 'public');

        //     $ticket->attachments()->create([
        //         'file_path' => $path,
        //         'file_name' => $filename,
        //         'date'=> now()
        //     ]);
        // }

        // Wysyłamy maila do admina
        $user = \App\Models\User::where('role', 'admin')->first();
        if($user) 
            Mail::to($user->email)->send(new \App\Mail\NewTicketMail($ticket));

        return redirect()->route('home');
    }

    public function edit(\App\Models\Ticket $ticket)
    {
        $categories = \App\Models\TicketCategory::all();
        $priorities = \App\Models\TicketPriority::all();
        // $statuses = \App\Models\TicketStatus::all();

        return view('tickets.edit', compact('ticket', 'categories', 'priorities'));
    }

    // Aktualizacja ticketa
    public function update(Request $request, \App\Models\Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'assigned_to' => 'nullable|exists:users,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'deadline' => 'required|date|',
        ]);


        if (auth()->user()->is_admin && isset($validated['assigned_to'])) {
            $ticket->worker_id = $validated['assigned_to'];
        }

        $ticket->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
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
      
        $ticket->update($validated);
      
     
        return redirect()->route('tickets.show',$ticket->id);
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
        return redirect()->route('home')->with('success', 'Ticket został usunięty.');
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
?>
