# Project Baseline & Technical Architecture

## 1. Overview
- **Project Name:** Almoneer General Portal (شبكة سماحة العلامة السيد منير الخباز - البوابة العامة والفكرية)
- **Codebase Path:** `C:\wamp\www\almoneer-gen`
- **Sister Projects:**
  - `almoneer` (`C:\wamp\www\almoneer` / `http://localhost:8000`): Hawza Academic Studies & Bahth Kharij Portal (بوابة الدروس الحوزوية والبحث الخارج)
  - `assfar-laravel`, `salma`, `qatarim`, `mawkeb-laravel`, `kalema-laravel` (Shared architecture patterns & CMS modules)
- **Stack:** Laravel 11+, PHP >= 8.2, MySQL, Vite / Tailwind CSS / Vanilla CSS, Alpine.js
- **Primary Domain (Target):** `almoneer.org`
- **Hawza Sub-Portal:** `droos.almoneer.org` (or `/droos` route / proxy to `almoneer_droos`)

---

## 2. Core Architecture & Architectural Philosophy

### A. Two-Portal Strategic Separation
1. **Hawza & Academic Portal (`almoneer_droos` / localhost:8000):**
   - High-level academic texts, Bahth Kharij lessons (Fiqh, Usul, Tafsir, Kalam, Philosophy, Mantiq, Rijal).
   - Class timetable, curriculum transcripts, specialized seminary audio files.
2. **General Public Portal (`almoneer-gen`):**
   - Community lectures, Ashura & Ramadan seasons, Friday sermons.
   - Books and publications with built-in PDF viewer.
   - Multimedia hub (Shorts/Reels, YouTube, Instagram, TikTok).
   - Inquiries & Q&A archive (الفتاوى والاستفسارات).
   - Poetry (شعر - ديوان القصائد الولائية).
   - Biography, activities, news, and contact forms.
3. **Cross-Portal Navigation:**
   - Both portals feature a prominent switcher badge in the top navigation bar to toggle smoothly between the General Portal and Hawza Portal.

---

## 3. High-Performance Caching & Cache-Busting Strategy

### Objective
Provide sub-second page loads to visitors via aggressive query/view caching, with **zero stale data** by automatically clearing relevant caches immediately upon any admin edit.

### Implementation Architecture
1. **Cache Layer:**
   - Public queries and home data are wrapped in `Cache::rememberForever()` or tagged cache:
     - `site.settings`: All site metadata and social links.
     - `site.menus.horizontal` & `site.menus.vertical`: HTML/JSON of navigation menus.
     - `site.home.data`: Aggregated home payload (featured lecture, word of the week, latest media, books, recent news).
     - `site.theme.active`: Currently activated theme name and config.
     - `site.categories.*`: Cached category trees.
2. **Automatic Invalidation (Admin Edits):**
   - Implemented via a reusable Eloquent Trait `App\Traits\ClearsFrontendCache` or Model Observers:
     - Any `saved()`, `updated()`, or `deleted()` event on `Article`, `MediaItem`, `Book`, `WeeklyWisdom`, `GalleryItem`, `Inquiry`, `Setting`, or `MenuItem` triggers cache invalidation for affected keys.
     - Updates to menus in `MenuController` immediately flush `site.menus.*` and `site.home.data`.
     - Updates to settings or themes in `ThemeController` flush `site.theme.*` and `site.settings`.
3. **Manual Admin Flush:**
   - One-click "تفريغ الكاش / Clear Cache" action in the Admin top navigation bar executing `Cache::flush()` and `Artisan::call('view:clear')`.

---

## 4. CMS Admin Core Capabilities

### A. Dual Menu Management (Horizontal & Vertical)
- Direct visual management for both:
  - **Horizontal Menu (`storage/app/menus/menu.htm`):** Top navigation bar for desktop and mobile slide-out drawer.
  - **Vertical Menu (`storage/app/menus/menuv.htm`):** Side navigation matching the legacy portal structure, accessible via desktop sidebar or off-canvas drawer.
- Drag-and-drop hierarchy and item reordering with AJAX persistence.
- Dynamic wizard to link menu items directly to Articles, Categories, Media, Galleries, or custom URLs.

### B. Theme Management System
- Theme directory: `public/site_assets/{theme_name}`.
- Theme manifest: `theme.json` containing name, author, screenshot, version, and custom color variables.
- Ability to upload theme `.zip` archives, preview, and switch active themes seamlessly via `ThemeController`.

### C. Inquiries & Fatwa Triage Workflow
- Status workflow: `New` ➔ `Under Review` ➔ `Answered (Private)` ➔ `Published to Public Bank`.
- Notifications sent to user via email upon answering.

### D. Audit Logging
- Modeled after `assfar-laravel` and `salma`:
  - Captures: `user_id`, `ip_address`, `action` (Create, Update, Delete, Toggle), `module`, `object_id`, and payload diff.

---

## 5. Mobile-First UX & Design Principles

### Visual Identity
- **Primary Scholarly Colors:** Deep Islamic Teal/Emerald (`#0a3d47` / `#0f4c5c`), Royal Muted Gold (`#c5942d` / `#d4aa48`).
- **Surface & Background:** Warm Off-White / Pearl (`#faf9f5`), White (`#ffffff`), Slate Gray (`#1e293b`).
- **Typography:**
  - Headers, quotes & poetry: `Amiri` and `Aref Ruqaa`.
  - UI, body text & navigation: `IBM Plex Sans Arabic`.
- **Mobile Enhancements:**
  - Sticky bottom navigation bar for mobile devices.
  - Persistent floating audio player with minimize/expand drawer.
  - Swipeable touch carousels for media, reels, and photo galleries.

---

## 6. Development & Deployment Guidelines
- All code follows strict PSR-12 and Laravel standard conventions.
- Never edit production files directly on the server.
- Database changes must always be managed through version-controlled Laravel migrations.
- Storage symlink (`php artisan storage:link`) connects `storage/app/public` to `public/storage`.
