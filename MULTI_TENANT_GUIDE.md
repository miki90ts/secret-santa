# Multi-Tenant Secret Santa - Dokumentacija

## Šta je urađeno?

Aplikacija je sada prilagođena za **multi-tenant** korišćenje, gde više firmi/organizacija može koristiti istu platformu sa potpuno izolovanim podacima.

## Nove funkcionalnosti

### 1. **Organizations (Organizacije)**

-   Svaka firma/grupa ima svoju organizaciju
-   Organizacija ima vlasnika (owner) i članove
-   Članovi mogu biti **admin** ili **member**

### 2. **Organization Members (Članovi)**

-   Korisnici mogu biti članovi više organizacija
-   Svaki član ima ulogu: `admin` ili `member`
-   Admin može kreirati evente, dodavati/uklanjati članove

### 3. **Event Isolation (Izolacija evenata)**

-   Eventi su vezani za organizaciju
-   Više organizacija može imati event sa istom godinom
-   Učesnici jednog eventa ne vide druge evente

### 4. **Public/Private Eventi**

-   Eventi mogu biti **privatni** (samo članovi organizacije)
-   Ili **javni** (bilo ko sa linkom može da se prijavi)

## Migracija postojećih podataka

✅ **Vaš postojeći event sa 30 ljudi je automatski migriran!**

Kreirana je "Default Organization" koja sadrži:

-   Sve postojeće korisnike (22 člana)
-   Vaš postojeći event sa svim parovima
-   Sve prijave, želje i dodele ostale netaknute

## Kako koristiti?

### Za postojeće korisnike:

1. Svi vaši podaci su sačuvani
2. Svi ste automatski dodati u "Default Organization"
3. Trenutni event nastavlja normalno da funkcioniše

### Za nove organizacije:

1. Bilo koji korisnik može kreirati novu organizaciju
2. Deli invite link (preko slug-a) sa kolegama
3. Kreira evente specifične za tu organizaciju
4. Članovi vide samo evente svoje organizacije

## API Endpoints

### Organizations

```php
GET    /organizations                    - Lista organizacija korisnika
POST   /organizations                    - Kreiraj novu organizaciju
GET    /organizations/{id}               - Detalji organizacije
PUT    /organizations/{id}               - Ažuriraj organizaciju
DELETE /organizations/{id}               - Obriši organizaciju
GET    /organizations/{slug}/join        - Pridruži se organizaciji
POST   /organizations/{id}/leave         - Napusti organizaciju
```

### Events (ažurirano)

```php
GET    /events                           - Eventi korisnikovih organizacija
POST   /events                           - Kreiraj event (mora organization_id)
GET    /events/{id}                      - Detalji eventa (sa org provera)
```

## Database Schema

### Nova tabela: `organizations`

-   `id` - Primary key
-   `name` - Naziv organizacije
-   `slug` - Jedinstveni URL slug (za invite)
-   `description` - Opis
-   `owner_id` - Vlasnik organizacije
-   `is_active` - Da li je aktivna

### Nova tabela: `organization_members`

-   `organization_id` - FK na organizations
-   `user_id` - FK na users
-   `role` - enum('admin', 'member')
-   `joined_at` - Datum pridruživanja

### Ažurirana tabela: `events`

-   `organization_id` - FK na organizations (NOVO)
-   `slug` - Jedinstveni slug za event (NOVO)
-   `year` - Više ne mora biti unique globalno
-   Unique constraint: `(organization_id, year)` - kombinacija

## Permissions & Policies

### OrganizationPolicy

-   `view` - Članovi + admin
-   `create` - Svi autentifikovani
-   `update` - Org admin + global admin
-   `delete` - Samo owner + global admin
-   `manageMembers` - Org admin + global admin

### EventPolicy (ažurirano)

-   `view` - Javni eventi ili članovi organizacije
-   `create` - Org admin + global admin
-   `update` - Org admin + global admin
-   `register` - Članovi organizacije + javni eventi

## Frontend (potrebno implementirati)

Potrebno je kreirati Vue komponente za:

1. **Organizations/Index.vue** - Lista organizacija
2. **Organizations/Create.vue** - Kreiranje nove organizacije
3. **Organizations/Show.vue** - Detalji organizacije, članovi, eventi
4. **Organizations/Edit.vue** - Izmena organizacije

Ažurirati postojeće:

-   **Events/Create.vue** - Dodati select za organizaciju
-   **Events/Index.vue** - Filter po organizaciji
-   **Dashboard.vue** - Prikaz organizacija korisnika

## Testiranje

```bash
# Seedovanje test organizacija (opciono)
php artisan db:seed --class=OrganizationSeeder
```

## Primer workflow-a

### Nova firma se pridružuje:

1. Admin firme kreira nalog
2. Kreira organizaciju "Moja Firma d.o.o."
3. Deli invite link: `/organizations/moja-firma-abc123/join`
4. Kolege otvaraju link i pridružuju se
5. Admin kreira Secret Santa event za 2025
6. Zaposleni se prijavljuju, izvlače parove
7. Firma XYZ ima potpuno odvojen event, ne vide se međusobno

## Šta dalje?

1. ✅ Backend je potpuno implementiran
2. ⏳ Frontend komponente treba kreirati
3. ⏳ Email notifikacije za pozivnice
4. ⏳ Dashboard sa pregledom organizacija
5. ⏳ Public events sa sharing linkovima
