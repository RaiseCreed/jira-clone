<form action="{{ route('tickets.attachments.store', $ticket->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="attachment">Dodaj załącznik:</label>
    <input type="file" name="attachment" id="attachment" required>
    <button type="submit">Wyślij</button>
</form>

//widok do zrrobienia jeszcze

