<<<<<<< HEAD
# students-project-management
=======
# Student Project Management System (SPMS)

A simple university semester project built with **core PHP + MySQL (mysqli)**, HTML5, Tailwind CSS (CDN), and PHP sessions.

## Features

- Landing page with hero, features, and footer
- Authentication (register, login, logout) with `password_hash()` / `password_verify()`
- Role-based access: **admin** and **student**
- Student: create / read / update / delete own projects, file uploads (PDF, JPG, PNG)
- Admin: view stats, manage users, manage projects, approve/reject, edit, delete
- Pagination on the admin projects page (10 per page)
- CSV export of all projects
- Tailwind CSS UI with sidebar, cards, responsive tables

## Folder Structure

```
/config
    db.php          - database connection (mysqli)
    auth.php        - session + role helper functions
    functions.php   - file upload + status badge helpers
/auth
    login.php
    register.php
    logout.php
/admin
    dashboard.php
    users.php
    projects.php    - includes pagination + approve/reject/edit/delete
    export.php      - CSV export
/student
    dashboard.php
    create-project.php
    my-projects.php
    edit-project.php
/includes
    header.php      - shared dashboard layout top
    sidebar.php     - role-aware sidebar
    footer.php
/uploads            - uploaded files are stored here
index.php           - landing page
setup-admin.php     - run once to create the admin account
database.sql        - database schema
```

## Setup Instructions

1. Copy the whole project into your web server folder (e.g. `htdocs/spms` for XAMPP).
2. Start **Apache** and **MySQL**.
3. Open **phpMyAdmin** and import `database.sql` (creates the `spms_db` database and tables).
4. If your MySQL has a password, set it in `config/db.php`.
5. Visit `http://localhost/spms/setup-admin.php` once to create the admin account, then **delete that file**.
6. Go to `http://localhost/spms/` to use the app.

## Default Admin Login

- Email: `admin@spms.com`
- Password: `admin123`

Students create their own accounts via the Register page.

## Notes

- Make sure the `/uploads` folder is writable by the web server.
- File uploads are limited to PDF, JPG, PNG and 5 MB.
>>>>>>> 73f2086 (feat: create sutdents project managment system)
