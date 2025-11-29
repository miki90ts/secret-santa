# 🎅 Secret Santa Aplikacija - Proširena Verzija

## 📋 Pregled

Secret Santa aplikacija omogućava organizaciju godišnjih razmena poklona u firmi. Svaki zaposleni može da se prijavi za učešće, unese svoje želje, a admin izvlači random parove. Aplikacija čuva istoriju želja kroz godine i omogućava praćenje zadovoljstva primljenim poklonom.

## ✨ Nove Funkcionalnosti

### 1. **Godišnji Događaji (Events)**
- Admin kreira događaje za svaku godinu (npr. Secret Santa 2024, 2025...)
- Definisanje perioda za prijave učesnika
- Označavanje aktivnog događaja
- Praćenje statusa (da li su izvršene dodele)

### 2. **Prijave Učesnika**
- Korisnici se prijavljuju za specifičan događaj/godinu
- Vidljiv broj prijavljenih učesnika
- Odjava sa događaja (pre izvlačenja)
- Pregled liste prijavljenih kolega

### 3. **Random Dodela (Assignment)**
- Admin funkcija za automatsko izvlačenje parova
- Algoritam osigurava da niko ne dobije sebe
- Svaki učesnik kupuje poklon jednoj osobi
- Dodela se čuva u bazi i ne može se ponoviti

### 4. **Vezivanje Želja za Godinu**
- Želje su vezane za konkretan događaj/godinu
- Korisnici mogu uneti različite želje svake godine
- Čuva se kompletna istorija želja kroz godine
- Pregled šta sam želeo u prošlim godinama

### 5. **Unos i Praćenje Primljenih Poklona**
- Korisnik unosi šta je dobio kao poklon
- Ocena zadovoljstva (zadovoljan/nije zadovoljan)
- Opcioni feedback/komentar o poklonu
- Javno vidljivo (ALI bez info ko je kupio - to ostaje tajna!)

### 6. **Privatnost i Anonimnost**
- Ko kome kupuje ostaje tajna tokom razmene
- Nakon dodele, korisnik vidi samo SVOJU osobu (kome kupuje)
- Primljeni pokloni su vidljivi svima ALI bez informacije ko je kupac
- Sugestije kolega su javne i pomažu u izboru poklona

### 7. **Admin Funkcionalnosti**
- Kreiranje i upravljanje događajima
- Označavanje aktivnog događaja
- Izvlačenje random parova
- Pregled svih učesnika i dodela
- Upravljanje godišnjim ciklusima

## 🗄️ Struktura Baze Podataka

### Nove Tabele

#### `events`
- `id` - ID događaja
- `year` - Godina (2024, 2025...)
- `name` - Naziv (npr. "Secret Santa 2024")
- `description` - Opis događaja
- `registration_start` - Početak perioda za prijave
- `registration_end` - Kraj perioda za prijave
- `assignment_date` - Datum kada je izvršena dodela
- `is_active` - Da li je aktivan događaj (samo jedan može biti aktivan)
- `assignments_made` - Da li su izvršene dodele

#### `event_participants`
- `id`
- `event_id` - Referenca na događaj
- `user_id` - Referenca na korisnika
- `registered_at` - Datum prijave
- Unique constraint: jedan user može biti samo jednom prijavljen po eventu

#### `assignments`
- `id`
- `event_id` - Referenca na događaj
- `giver_id` - Ko kupuje (referenca na users)
- `receiver_id` - Kome kupuje (referenca na users)
- Unique constraint: jedan giver može imati samo jedan receiver po eventu

#### `gifts`
- `id`
- `assignment_id` - Referenca na dodelu
- `description` - Opis poklona (šta je primljeno)
- `is_satisfied` - Boolean - da li je zadovoljan
- `feedback` - Komentar o poklonu
- `received_at` - Datum prijema

### Izmenjene Tabele

#### `users`
- Dodato: `is_admin` - Boolean flag za admin korisnike

#### `wishes`
- Dodato: `event_id` - Vezivanje želje za konkretan događaj
- Unique constraint: jedan user može imati samo jedan wish po eventu

## 🚀 Instalacija i Podešavanje

### 1. Migracije

Prvo, pokreni nove migracije:

```powershell
php artisan migrate
```

Ovo će kreirati sve nove tabele i dodati nove kolone.

### 2. Seeding (Opciono)

Za test podatke sa prvim adminom i eventima:

```powershell
php artisan db:seed
```

Ovo će kreirati:
- Admin korisnika: `admin@example.com` / `password`
- Test korisnika: `test@example.com` / `password`
- 18 dodatnih korisnika
- 2 događaja (aktivan za ovu godinu i prošlogodišnji)
- 10 prijavljenih učesnika za aktivan događaj

### 3. Kreiranje Admin Korisnika (Ručno)

Ako ne želiš seeder, možeš ručno postaviti admin flag u bazi:

```sql
UPDATE users SET is_admin = 1 WHERE email = 'tvoj@email.com';
```

## 📱 Korišćenje Aplikacije

### Za Admina

1. **Kreiranje Događaja**
   - Idi na `/events`
   - Klikni "Kreiraj Novi Događaj"
   - Unesi godinu, naziv, opis, periode prijava
   - Označi kao aktivan

2. **Izvlačenje Parova**
   - Sačekaj da se korisnici prijave
   - Otvori događaj (`/events/{id}`)
   - Klikni "Izvuci Parove"
   - Sistem će random dodeliti parove

3. **Praćenje**
   - Pregled prijavljenih učesnika
   - Praćenje ko je uneo želje
   - Pregled poklona (bez info ko je kupac)

### Za Korisnike

1. **Prijava za Događaj**
   - Idi na `/events`
   - Otvori aktivan događaj
   - Klikni "Prijavi se"

2. **Unos Želja**
   - Na Dashboard-u unesi šta voliš a šta ne
   - Kolege mogu ostaviti sugestije
   - Možeš videti istoriju želja kroz godine (`/wishes/history`)

3. **Nakon Dodele**
   - Idi na događaj i klikni "Vidi Kome Kupuješ"
   - Vidiš ime osobe i njene želje
   - Sugestije kolega ti pomažu u izboru

4. **Unos Primljenog Poklona**
   - Nakon što dobiješ poklon
   - Unesi šta si dobio
   - Oceni zadovoljstvo (smiley/frowny)
   - Ostavi feedback

## 🛣️ Važni Rute

### Eventi
- `GET /events` - Lista svih događaja
- `GET /events/create` - Forma za kreiranje (admin only)
- `POST /events` - Čuvanje novog događaja (admin only)
- `GET /events/{event}` - Detalji događaja
- `GET /events/{event}/edit` - Izmena događaja (admin only)
- `PATCH /events/{event}` - Ažuriranje događaja (admin only)
- `POST /events/{event}/register` - Prijava korisnika
- `DELETE /events/{event}/unregister` - Odjava korisnika

### Dodele (Assignments)
- `POST /events/{event}/assignments` - Izvlačenje parova (admin only)
- `GET /events/{event}/my-assignment` - Prikaz kome kupujem

### Pokloni (Gifts)
- `POST /assignments/{assignment}/gift` - Unos primljenog poklona
- `GET /gifts?event_id={id}` - Lista poklona za događaj

### Želje (Wishes)
- `POST /wishes` - Kreiranje/ažuriranje želje
- `GET /wishes/history` - Istorija mojih želja

## 🔐 Autorizacija (Policies)

### EventPolicy
- `viewAny`, `view` - Svi mogu videti
- `create`, `update`, `delete` - Samo admin
- `makeAssignments` - Admin i ako nisu već izvršene
- `register` - Ako su prijave otvorene i korisnik nije već prijavljen

### AssignmentPolicy
- `view` - Korisnik vidi samo svoju dodelu (kome kupuje)
- `viewAny` - Samo admin vidi sve dodele

### GiftPolicy
- `view` - Svi mogu videti poklone (ALI bez info ko je kupio)
- `update` - Samo receiver može uneti svoj poklon

## 🎨 Frontend Stranice (Vue)

### Events
- `Events/Index.vue` - Lista svih događaja
- `Events/Show.vue` - Detalji događaja, učesnici, prijava
- `Events/Create.vue` - Forma za kreiranje događaja (admin)

### Assignments
- `Assignments/Show.vue` - Prikaz kome kupujem + forma za unos poklona

### Gifts
- `Gifts/Index.vue` - Lista primljenih poklona (bez kupaca)

### Wishes
- `Wishes/History.vue` - Istorija mojih želja kroz godine

## 🔄 Workflow za Godišnji Ciklus

1. **Novembar/Decembar** - Admin kreira novi događaj za narednu godinu
2. **Otvaranje Prijava** - Korisnici se prijavljuju (npr. 1-15. decembar)
3. **Unos Želja** - Učesnici unose šta vole/ne vole
4. **Sugestije** - Kolege ostavljaju sugestije
5. **Zatvaranje Prijava** - 15. decembar
6. **Izvlačenje** - Admin klikne "Izvuci Parove" (16. decembar)
7. **Kupovina** - Svako vidi svoju osobu i kupuje poklon (16-20. decembar)
8. **Razmena** - Razmena poklona na firmskoj proslavi (21. decembar)
9. **Feedback** - Korisnici unose šta su dobili i ostavljaju ocenu
10. **Arhiva** - Sledeće godine se ponavlja proces, prošlogodišnji podaci ostaju sačuvani

## 🛠️ Dodatne Komande

### Kreiranje Test Podataka

```powershell
# Fresh start sa svim test podacima
php artisan migrate:fresh --seed

# Samo seedovanje bez resetovanja
php artisan db:seed
```

### Čišćenje Cache-a

```powershell
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📊 Modeli i Relacije

### User
- `hasMany(Wish)` - Sve želje korisnika kroz godine
- `belongsToMany(Event)` - Događaji u kojima učestvuje
- `hasMany(Assignment, 'giver_id')` - Dodele gde kupuje
- `hasMany(Assignment, 'receiver_id')` - Dodele gde prima
- `hasMany(Comment)` - Komentari/sugestije

### Event
- `belongsToMany(User)` - Učesnici
- `hasMany(Wish)` - Želje za ovaj događaj
- `hasMany(Assignment)` - Dodele za ovaj događaj

### Wish
- `belongsTo(User)` - Vlasnik želje
- `belongsTo(Event)` - Događaj
- `hasMany(Comment)` - Komentari na želju

### Assignment
- `belongsTo(Event)` - Događaj
- `belongsTo(User, 'giver_id')` - Ko kupuje
- `belongsTo(User, 'receiver_id')` - Ko prima
- `hasOne(Gift)` - Primljeni poklon

### Gift
- `belongsTo(Assignment)` - Dodela

## 🔮 Ideje za Buduće Proširenje

- Email notifikacije kada su dodele izvršene
- Budget limit za poklone
- Kategorije poklona (knjige, tech, sport...)
- Upload slika primljenih poklona
- Rating sistem (zvezdice)
- Statistika (najbolji poklonodavci)
- Wishlist sa linkovima ka proizvodima
- Multi-language support
- Dark mode

## 📝 Napomene

- **Tajna se MORA čuvati!** - Sistem ne prikazuje ko je kome kupio poklon
- Admin ima posebne privilegije ali i odgovornost
- Dodele se mogu izvršiti samo jednom po događaju
- Prijave su moguće samo u definisanom periodu
- Jedan user može imati samo jednu želju po događaju
- Istorija se čuva zauvek - nikad se ne briše

## 🤝 Doprinos

Ovo je bazna verzija. Možeš dodavati nove funkcionalnosti prema potrebama firme!

Srećan Secret Santa! 🎅🎁
