# 🎓 University Project Management System

A web-based platform built with **PHP** and **Tailwind CSS** designed to streamline the submission, review, and management of university student projects. It features role-based access control for **Students** and **Admins**.

---

## 🚀 Key Features

### 👨‍🎓 Student Dashboard
* **Secure Authentication:** Easy registration and login system.
* **Project CRUD:** Create, read, update, and delete project submissions.
* **Status Analytics:** Visual counters for **Approved**, **Rejected**, **Pending**, and **Total** projects.
* **Detailed View:** Full overview of project history and feedback on the main dashboard.

### 👨‍💼 Admin Panel
* **User Management:** View and manage all registered student accounts.
* **Project Moderation:** Review details to **Approve** or **Reject** submissions.
* **Data Control:** Full edit and delete permissions for any project.
* **CSV Export:** One-click data export of all projects for academic reporting.

---

## 🛠️ Tech Stack

* **Backend:** PHP (OOP / Procedural)
* **Frontend:** Tailwind CSS (Responsive Design)
* **Database:** MySQL
---

## 💻 Installation & Setup

Follow these steps to run the project locally:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Mohsen-Parsa11/students-project-management.git
   cd your-repo-name
   ```

2. **Set up the Database:**
   * Open XAMPP/WAMP and start **Apache** and **MySQL**.
   * Go to `http://localhost/phpmyadmin/`.
   * Create a new database (e.g., `spms_db2`).
   * Import the provided `.sql` file located in the database folder.

3. **Configure Database Connection:**
   * Open `config.php` or database connection file.
   * Update credentials:
     ```php
     define('DB_SERVER', 'localhost');
     define('DB_USERNAME', 'root');
     define('DB_PASSWORD', '');
     define('DB_NAME', 'spms_db2');
     ```

4. **Run the Application:**
   * Move the project folder to your local server directory (e.g., `htdocs`).
   * Open your browser and navigate to `http://localhost/your-repo-name`.

---

## 🔑 Default Credentials (For Testing)

* **Admin Email:** `jumaa@gmail.com` | **Password:** `12345678`
* **Student Email:** `mujtaba@gmail.com` | **Password:** `mujtaba123`

