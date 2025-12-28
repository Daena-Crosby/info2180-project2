# INFO2180 Project 2 – Dolphin CRM

## Group Members
1. **Daena Crosby (620162441)** – Front End  
2. **Tonia Williams(620162352)** – Database  
3. **Alexander Waite (620165566)** – Backend (Authentication & User Management)  
4. **Hector Riettie (620161458)** – Backend (Contacts & Notes)  

---

## UI / Frontend (Daena Crosby)
- Build the **HTML/CSS/JS layouts** for:
  - Login page  
  - Dashboard with filters & contact list  
  - User creation form  
  - Contact creation form  
  - Contact details + notes section  
- Ensure **AJAX integration hooks** are in place (buttons/forms trigger JS requests without page reload).  
- Focus on **responsive design** and polished UX.  

---

## Database & Schema (Tonia Williams)
- Create the **`schema.sql`** file with `CREATE TABLE` statements for:
  - `users`  
  - `contacts`  
  - `notes`  
- Add the **initial admin user** with hashed password (`admin@project2.com` / `password123`).  
- Ensure proper **foreign keys** (e.g., `assigned_to`, `created_by`, `contact_id`).  
- Handle **constraints** (e.g., NOT NULL, VARCHAR lengths).  
- Test queries for filtering contacts (Sales Lead, Support, Assigned to me).  

---

## Backend / Authentication & User Management (Alexander Waite)
- Implement **login/logout** with PHP sessions.  
- Build **user creation form handling**:
  - Regex validation for password strength.  
  - Use `password_hash()` for secure storage.  
  - Input sanitization & escaping.  
- Create **user listing page** (Admin-only).  
- Handle **session destruction** on logout.  

---

## Backend / Contacts & Notes (Hector Riettie)
- Implement **contact creation form handling**:
  - Validate inputs, sanitize, store `created_by`, `created_at`, `updated_at`.  
- Build **dashboard filters** (All, Sales Leads, Support, Assigned to me).  
- Implement **contact details view**:
  - Show full info, allow "Assign to me" and "Switch type".  
  - Update `updated_at` when changes occur.  
- Handle **notes system**:
  - Display notes with author + timestamp.  
  - Add new notes, update contact’s `updated_at`.  
- Ensure all actions use **AJAX** (no page refresh).  

---

## ✅ Summary of Roles

| Person            | Role                        | Responsibilities                                      |
|-------------------|-----------------------------|------------------------------------------------------|
| **Daena Crosby**  | UI / Frontend               | Build layouts, forms, dashboard, AJAX hooks          |
| **Tonia Williams**| Database                    | Schema, tables, initial data, queries                |
| **Alexander Waite** | Backend (Users)          | Login/logout, user creation, validation, session mgmt|
| **Hector Riettie**| Backend (Contacts/Notes)    | Contact CRUD, filters, notes, AJAX                   |

---

## 📖 Project Overview
This project delivers a **Minimum Viable Product (MVP)** for *Dolphin CRM*.  
Each team member has **distinct ownership** of their part, ensuring smooth integration:
- Frontend/UI layer  
- Database schema & setup  
- Authentication & user management  
- Contacts & notes management  

Together, these components form a functional CRM system with login, user management, contact handling, notes, and AJAX-powered interactivity.
