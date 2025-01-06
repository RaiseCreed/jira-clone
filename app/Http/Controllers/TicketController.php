<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function storeAttachment(Request $request, $ticketId)
    {
    
        $request->validate([
            'attachment' => 'required|file|max:2048', // 2mb 
        ]);

       
        $fileBlob = file_get_contents($request->file('attachment')->getRealPath());

        // Zapis załącznika w bazie danych
        Attachment::create([
            'ticket_id' => $ticketId,
            'blob' => $fileBlob,
            'date' => now(),
        ]);

        return back()->with('success', 'Załącznik został przesłany');
    }

    public function downloadAttachment($attachmentId)
    {
        
        $attachment = Attachment::findOrFail($attachmentId);

     
        return response($attachment->blob)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="attachment_' . $attachment->id . '"');
    }
}
