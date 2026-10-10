# Task Management System (PHP OOP)

A two-role task management web app built with **PHP (OOP)**, **MySQL**, and **Bootstrap 5**. Built as a university project.

## Roles

**Admin**
- Add, edit, and delete users
- View, edit, and delete all tasks

**User**
- Register and log in
- Add, edit, and delete their own tasks
- Update their profile and change their password

## Features

- Object-oriented structure: `Database` (singleton), `User`, and `Task` classes
- Passwords hashed with BCrypt (`password_hash` / `password_verify`)
- Prepared statements (`mysqli`) for every query, protecting against SQL injection
- Ownership checks: users can only update or delete their own tasks, admins can manage all
- Session-based authentication

## Tech Stack

PHP · MySQL (mysqli) · Bootstrap 5 · HTML/CSS

## Getting Started

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Clone the repo into `htdocs`:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/marwaammed9/TaskManagementSystem.git task_manager
   ```
3. Open `http://localhost/phpmyadmin`, go to **Import**, and import `database.sql`. This creates the `task_manager` database, the tables, and demo data.
4. Check `config.php` matches your setup (default XAMPP: user `root`, empty password).
5. Open `http://localhost/task_manager/`.

## Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | Admin@123 |
| User | user@example.com | User@123 |

## Project Structure

```
task_manager/
├── Admin/        # Admin pages
├── Classes/      # Database, User, Task classes
├── config.php    # Database settings
├── database.sql  # Database schema and demo data
└── *.php         # Login, register, dashboard, task pages
```

## Author

**Marwa Mohammed Basalah**, [GitHub](https://github.com/marwaammed9) · [LinkedIn](https://www.linkedin.com/in/marwa-mohammed-2718a0270)
