<?php

namespace App\Http\Controllers;

use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;        
use Illuminate\Support\Facades\DB;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Carbon\Carbon; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::query();
        $dashboardData = self::dashboardDataArray();

        switch ($user->role) {
            case 'customer': 
                $query->where('owner_id', $user->id);
                break;
            case 'worker':
                $query->where('worker_id', $user->id);
                break;
        }

        if ($user->role === 'admin' && !$request->has('worker')) {
            $request->merge(['worker' => 'unassigned']);
        }
        

        if ($request->has('title') && $request->title != '') {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('ticket_category_id', $request->category);
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('ticket_priority_id', $request->priority);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('ticket_status_id', $request->status);
        }

        if ($request->has('deadline') && $request->deadline != '') {
            $query->whereDate('deadline', $request->deadline);
        }

        if ($request->has('worker') && $request->worker != '') {
            if($request->worker == 'unassigned') {
                $query->whereNull('worker_id');
            } else {
                $query->where('worker_id', $request->worker);
            }
        }
        

        $tickets = $query->paginate(10);
        $categories = TicketCategory::all();
        $priorities = TicketPriority::all();
        $statuses = TicketStatus::all();
        $workers = User::where('role','worker')->get();
        $totalTickets = Ticket::count();

        $workloads = Ticket::selectRaw('worker_id, COUNT(*) as workload')
            ->whereNotNull('worker_id')
            ->groupBy('worker_id')
            ->pluck('workload', 'worker_id');
        
        $workers = $workers->map(function ($worker) use ($workloads, $totalTickets) {
            $workloadCount = $workloads->get($worker->id, 0); 
                $worker->workload_percentage = $totalTickets > 0 
                ? round(($workloadCount / $totalTickets) * 100, 2) 
                : 0; 
            return $worker;
        });
        $quote = self::getQuote();
        return view('home', [
            'tickets' => $tickets,
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'workers' => $workers,
            'quote' => $quote,
            'ticketsByPriority' => $dashboardData['tickets_by_priority'],
            'ticketsByDueTime' => $dashboardData['tickets_by_due_time'],
        ]);
    }
  
    private static function getQuote()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.api-ninjas.com/v1/quotes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "X-Api-Key: iK4R6CNSTPdGgwhlLZ5PT9lzoQbQkzQPC8o1hAwA" // Will be cancelled after presentation
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        if (is_array($response)) { 
            return $response[0];
        }
    }

    public function dashboardDataArray()
    {
        $userId = auth()->id();
        
        $field = (auth()->user()->role === 'customer') ? 'owner_id' : 'worker_id';
    
        $ticketsByPriority = \App\Models\TicketPriority::leftJoin('tickets', function ($join) use ($userId,$field) {
            $join->on('ticket_priorities.id', '=', 'tickets.ticket_priority_id')
                ->where('tickets.'.$field, '=', $userId);
        })
        ->select('ticket_priorities.name', DB::raw('COUNT(tickets.id) as count'))
        ->groupBy('ticket_priorities.name')
        ->get()
        ->pluck('count', 'name')
        ->toArray();
     
        $ticketsByDueTime = [
            'overdue' => Ticket::where($field, $userId)
               ->where('deadline', '<', now())
               ->count(),
            'today' => Ticket::where($field, $userId)
                ->whereDate('deadline', now()->toDateString())
                ->count(),
            'tomorrow' => Ticket::where($field, $userId)
                ->whereDate('deadline', now()->addDay()->toDateString())
                ->count(),
            'later' => Ticket::where($field, $userId)
                ->where('deadline', '>', now()->addDay()->endOfDay())
                ->count(),
        ];
    
        // Zwracane dane jako tablica
        return [
            'tickets_by_priority' => $ticketsByPriority,
            'tickets_by_due_time' => $ticketsByDueTime,
        ];
    }
    
    public static function addMockData()
    {
         // Tworzenie przykładowych kategorii, priorytetów i statusów
          $category1 = TicketCategory::create([
          'name' => 'Błąd'
           ]);
           $category2 = TicketCategory::create([
          'name'=> 'Usprawnienia'
           ]);

         $priority1 = TicketPriority::create([
          'name' => 'Wysoki'
         ]);
         $priority2 = TicketPriority::create([
         'name' => 'średni'
         ]);
         $priority3 = TicketPriority::create([
          'name'=> 'niski'
          ]);
         $status1 = TicketStatus::create([
         'name' => 'W trakcie'
          ]);
          $status2 = TicketStatus::create([
         'name' => 'nowy' 
          ]);
          $status3 = TicketStatus::create([
          'name' => 'w oczekiwaniu na odpowiedz'
          ]);               
                         

        // Tworzenie przykładowych użytkowników
        $customer1 = User::create([
            'name' => 'Jan Kowalski',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $customer2 = User::create([
            'name' => 'Bartosz Kamiński',
            'email' => 'batoszkam@wp.pl',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
        $customer3 = User::create([ 
            'name' => 'Kamil Osiński',
            'email' => 'osinski@gmail.com',
            'password' => bcrypt('haslo'),
            'role' => 'customer',
        ]);
        $worker1 = User::create([
            'name' => 'Anna Nowak',
            'email' => 'Annanowak@example.com',
            'password' => bcrypt('password'),
            'role' => 'worker',
        ]);
        $worker2 = User::create([       
            'name' => 'Michał Polak',
            'email' => 'Polak@example.com',
            'password' => bcrypt('polak'),
            'role' => 'worker',
            ]);
        $ticket1 = Ticket::create([
            'ticket_category_id' => $category1->id,
            'ticket_priority_id' => $priority1->id,
            'ticket_status_id' => $status1->id,
            'owner_id' => $customer1->id, 
            'worker_id' => $worker1->id,  
            'title' => 'Błąd w aplikacji',
            'date' => Carbon::now(),
            'deadline' => Carbon::now()->addDays(3),
            'content' => 'Opis błędu w aplikacji.',
              ]);
              $ticket2 = Ticket::create([
                'ticket_category_id' => $category1->id,
                'ticket_priority_id' => $priority1->id,
                'ticket_status_id' => $status1->id,
                'owner_id' => $customer1->id, 
                'worker_id' => $worker2->id,  
                'title' => 'Błąd w formularzu logowania',
                'date' => Carbon::now(),
                'deadline' => Carbon::now()->addDays(5),
                'content' => 'Użytkownicy nie mogą się zalogować z powodu błędu 500.',
                  ]);
                  $ticket3 = Ticket::create([
                    'ticket_category_id' => $category1->id,
                    'ticket_priority_id' => $priority1->id,
                    'ticket_status_id' => $status2->id,
                    'owner_id' => $customer2->id, 
                    'worker_id' => $worker2->id,  
                    'title' => 'Brak dostępu do panelu administracyjnego',
                    'date' => Carbon::now(),
                    'deadline' => Carbon::now()->addDays(4),
                    'content' => 'Administratorzy nie mogą otworzyć panelu zarządzania.',
                      ]);
                      $ticket4 = Ticket::create([
                        'ticket_category_id' => $category1->id,
                        'ticket_priority_id' => $priority1->id,
                        'ticket_status_id' => $status2->id,
                        'owner_id' => $customer3->id, 
                        'worker_id' => $worker1->id,  
                        'title' => 'Reset hasła nie działa',
                        'date' => Carbon::now(),
                        'deadline' => Carbon::now()->addDays(2),
                        'content' => 'Przycisk resetowania hasła wyświetla komunikat o błędzie.',
                          ]);
                          $ticket5 = Ticket::create([
                            'ticket_category_id' => $category2->id,
                            'ticket_priority_id' => $priority2->id,
                            'ticket_status_id' => $status3->id,
                            'owner_id' => $customer3->id, 
                            'worker_id' => $worker2->id,  
                            'title' => 'Integracja z zewnętrznym API',
                            'date' => Carbon::now(),
                            'deadline' => Carbon::now()->addDays(7),
                            'content' => 'Proszę wdrożyć integrację z API dostarczanym przez partnera',
                              ]);
                              $ticket6 = Ticket::create( [
                                'ticket_category_id' => $category1->id,
                                'ticket_priority_id' => $priority1->id,
                                'ticket_status_id' => $status2->id,
                                'owner_id' => $customer2->id, 
                                'worker_id' => $worker1->id,  
                                'title' => 'Problem z realizacją płatności',
                                'date' => Carbon::now(),
                                'deadline' => Carbon::now()->addDays(4),
                                'content' => 'Płatność online nie została zrealizowana, a środki zostały pobrane z konta',
                                  ]);
                                     
        TicketComment::create([    
        ['ticket_id' => $ticket1, 'user_id' => $worker1->id, 'content' => 'Zbadam ten problem i wrócę z rozwiązaniem.'],
        ['ticket_id' => $ticket1, 'user_id' => $customer1->id, 'content' => 'Dziękuję za szybką reakcję.'],
        ['ticket_id' => $ticket1, 'user_id' => $worker1->id, 'content' => 'Problem zidentyfikowany. Aktualizuję kod aplikacji.'],
        ]); 
        TicketComment::create([ 
            ['ticket_id' => $ticket2, 'user_id' => $worker1->id, 'content' => 'Zbadam ten problem i wrócę z rozwiązaniem.'],
            ['ticket_id' => $ticket2, 'user_id' => $customer2->id, 'content' => 'Dziękuję za szybką reakcję.'],
            ['ticket_id' => $ticket2, 'user_id' => $worker1->id, 'content' => 'Problem zidentyfikowany. Aktualizuję kod aplikacji.'],
            ]);

        TicketComment::create([
        
            ['ticket_id' => $ticket3, 'user_id' => $worker2->id, 'content' => 'Sprawdzam dostępność panelu administracyjnego.'],
            ['ticket_id' => $ticket3, 'user_id' => $customer3->id, 'content' => 'Proszę o pilne rozwiązanie tego problemu.'],
            ['ticket_id' => $ticket3, 'user_id' => $worker2->id, 'content' => 'Naprawiono. Panel jest już dostępny.'],
         ]);
         TicketComment::create([ 
                ['ticket_id' => $ticket4, 'user_id' => $worker2->id, 'content' => 'Przycisk resetowania hasła wymaga poprawy w backendzie.'],
                ['ticket_id' => $ticket4, 'user_id' => $customer2->id, 'content' => 'Kiedy mogę się spodziewać rozwiązania?'],
                ['ticket_id' => $ticket4, 'user_id' => $worker2->id, 'content' => 'Poprawka zostanie wdrożona do końca dnia.'],
         ]);
         TicketComment::create([
         
            ['ticket_id' => $ticket5, 'user_id' => $worker1->id, 'content' => 'Integracja z API partnera wymaga dodatkowej dokumentacji.'],
            ['ticket_id' => $ticket5, 'user_id' => $customer1->id, 'content' => 'Dostarczę dokumentację API w najbliższym czasie.'],
            ['ticket_id' => $ticket5, 'user_id' => $worker1->id, 'content' => 'Prace rozpoczną się, gdy dokumentacja będzie dostępna.'],
        ]);
        TicketComment::create([
        
            ['ticket_id' => $ticket6, 'user_id' => $worker1->id, 'content' => 'Prace rozpoczną się, gdy dokumentacja będzie dostępna.'],
            ['ticket_id' => $ticket6, 'user_id' => $worker1->id, 'content' => 'Problem z realizacją płatności sprawdzony w systemie.'],
            ['ticket_id' => $ticket6, 'user_id' => $customer3->id, 'content' => 'Czy mogę prosić o zwrot środków?'],
            ['ticket_id' => $ticket6, 'user_id' => $worker1->id, 'content' => 'Środki zostaną zwrócone w ciągu 3 dni roboczych.'],
        ]);

    }
    public static function removeMockData()
    {
        // Usuń tickety na podstawie ich tytułów
        Ticket::whereIn('title', [
            'Błąd w aplikacji',
            'Błąd w formularzu logowania',
            'Brak dostępu do panelu administracyjnego',
            'Reset hasła nie działa',
            'Integracja z zewnętrznym API',
            'Problem z realizacją płatności',
        ])->delete();

        // Usuń użytkowników na podstawie emaili
        User::whereIn('email', [
            'customer@example.com',
            'batoszkam@wp.pl',
            'osinski@gmail.com',
            'Annanowak@example.com',
            'Polak@example.com',
        ])->delete();

        // Usuń komentarze 
        TicketComment::whereIn('content', [
            'Zbadam ten problem i wrócę z rozwiązaniem.',
            'Dziękuję za szybką reakcję.',
            'Problem zidentyfikowany. Aktualizuję kod aplikacji.',
            'Sprawdzam dostępność panelu administracyjnego.',
            'Proszę o pilne rozwiązanie tego problemu.',
            'Naprawiono. Panel jest już dostępny.',
            'Przycisk resetowania hasła wymaga poprawy w backendzie.',
            'Kiedy mogę się spodziewać rozwiązania?',
            'Poprawka zostanie wdrożona do końca dnia.',
            'Integracja z API partnera wymaga dodatkowej dokumentacji.',
            'Dostarczę dokumentację API w najbliższym czasie.',
            'Prace rozpoczną się, gdy dokumentacja będzie dostępna.',
            'Problem z realizacją płatności sprawdzony w systemie.',
            'Czy mogę prosić o zwrot środków?',
            'Środki zostaną zwrócone w ciągu 3 dni roboczych.',
        ])->delete();

        TicketCategory::whereIn('name', ['Błąd', 'Usprawnienia'])->delete();
        TicketPriority::whereIn('name', ['Wysoki', 'średni', 'niski'])->delete();
        TicketStatus::whereIn('name', ['W trakcie', 'nowy', 'w oczekiwaniu na odpowiedz'])->delete();
    }
}
