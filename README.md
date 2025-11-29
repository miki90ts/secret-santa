# 🎅 Secret Santa Aplikacija

Laravel aplikacija za organizaciju Secret Santa poklona u firmi.

## 🚀 Funkcionalnosti

- ✅ Godišnji Secret Santa događaji
- ✅ Registracija učesnika
- ✅ Automatsko random izvlačenje parova
- ✅ Unos želja (šta volim/ne volim)
- ✅ Sugestije kolega
- ✅ Istorija želja kroz godine
- ✅ Anonimno prijavljivanje primljenih poklona
- ✅ Ocena zadovoljstva

## 📋 Tehnologije

- Laravel 11.31
- Vue 3 + Inertia.js 1.0
- Tailwind CSS
- MySQL

## 🛠️ Instalacija

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run dev
