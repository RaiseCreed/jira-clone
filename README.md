# jira-clone

Założenia - system do ticketów

/// Dominik Nowacki uwagi

Widok usera:

User ma stronę logowanie (opcja zmiany hasła ? opcjonalnie). Loguje się do systemu i widzi dashboard z podsumowaniem typu ile ticketów wysłał, czy ma nowe wiadomości (do ustalenia jeszcze jakie info dajemy)

Core programu to opcja dodawania ticketa jakimś plusem. Odpala się wtedy widok całęgo zgłoszenia i pola na np. nazwę kategorię, załącznik, priorytet, komentarz, opis etc.

User zgłasza ticket a potem zostaje przekierowany znowu do Dashboardu.

W panelu nawigacji możemy dać ustawienia konta (username, zdjęcie profilowe ?, zmiana loginu / hasła ?)



Widok "admina":

Admin wchodzi przez stronę logowania i jego dashboard zawiera info o tym ile ticketów ma przypisanych etc. Na pewno w panelu nawigacji dajemy wejście do widoku, gdzie można ustatać jaki pracownik(admin) ma jaką przypisaną kategorię i wtedy mozna przypisywac na podstawie kategorii właśnie .


Zarównoo admin jak i user mogą mieć w zakładkach widok do wysyłanmia wiadomości w systemie.

Ja to bym widział jako "baza". Potem to już kwestia rozwoju.



Potencjalne ficzery:
- Plugin Discord
- Ściąganie kursów z NBP
- Wysyłanie maili przy zmianach fazy zleceń

/// Piotr Łukaszewski uwagi

Można się zastanowić nad resetem hasła z wysyłaniem maila + generowanie linku do resetu w backendzie.

Zapewne gdzieś przydałby się widok z listą ticketów + filtry, może zapisywanie zestawów filtrów per user.

Zamiast określania uprawnień per rola, zrobiłbym na zasadzie macierzy uprawnień, tj. tabela z uprawnieniami i tabela asocjacyjna rola-uprawnienie. Trzeba będzie rozpisać początkowe założenia i przydzielenie uprawnień dla danych roli, reszta wyjdzie w trakcie.

Po skończeniu z readmie, zrobiłbym maina jako protected, noweo brancha np. develop też protected i każdy zrobi swojego brancha i będziemy je mergować do developa.

/// Dominik Nytz uwagi
Założenia po stronie bazy:

 - ticket_status - podczas tworzenia ticketa należy nadać ticket_status_id na 1 (aktywny),
 - ticket_priority - ticket_priority_id będzie nadawane podczas przydzielania zadania do użytkownika obsługującego,
 - ticket_category - j.w.
 - deadline - musimy oprogramować stałe wartości doliczania czasu od momentu dodania zgłoszenia w zależności jaki priorytet został nadany,
 - date - nadawane z daty bieżącej w formacie yyyy-mm-dd hh:mm:ss,
 - po zakończeniu zgłoszenia informacja ile czasu zajęła realizacja, ewentualnie ile czasu mogłaby zająć,
 - attachement - trzeba określić jakiś maksymalny rozmiar załącznika który może dodać użytkownik,
 - users - użytkownik musi mieć nadaną rolę z listy podczas tworzenia,
 - permisions - kolumna function jest opcjinalna, jeśli uprawnienia zostaną oprogramowane na podstawie nazwy - jest niepotrzebna
