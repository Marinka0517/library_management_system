# Library Management System

A database-driven web application built with **PHP and MySQL** that simulates how a real library manages books, readers, and administrative operations.

This project was developed as my Database Management Systems final project.

The goal was to **design a complete system including database design, role-based access control, and web interface for library operations**.

# Key Features

The system supports multiple user roles with different permissions.

### Reader
Readers can:
- Search books
- Borrow books
- Return books
- View fine records
- Edit personal information

### Librarian
Librarians can:
- Add new books
- Edit book information
- Delete books
- Borrow books for readers
- Process book returns

### Admin
Admins can:
- Search books
- Maintain book records
- Manage user accounts
- Perform system maintenance

### SuperAdmin
SuperAdmin has full system control and can:
- Manage administrator accounts
- View system statistics
- Access all system functions

# Technologies Used

This project was implemented using the following technologies:

- **PHP** – backend logic
- **MySQL** – relational database
- **XAMPP** – local development server
- **phpMyAdmin** – database management
- **HTML / CSS** – interface design
- **JavaScript** – basic UI interaction

# System Architecture

The system follows a simple web application architecture:

User Interface → PHP Backend → MySQL Database

Users interact with the web interface, PHP processes requests, and MySQL stores and retrieves data.

# Database Design

The database was designed using:
- Entity Relationship Diagram (ERD)
- Logical Data Model
- Physical Data Model
- Database Normalization (up to 3NF)

# What I Learned

Through this project, I gained practical experience in:
- Designing relational database schemas
- Creating ER diagrams and normalized tables
- Implementing CRUD operations
- Connecting PHP with MySQL
- Managing user roles and permissions
- Building a multi-page web application
- Understanding how backend systems interact with databases

This project helped me better understand how database systems support real-world applications.

# How to Run the Project

If you want to run the project on your computer, you can follow these steps.

1. Install XAMPP.
2. Put the Library folder inside the htdocs folder.
3. Start Apache and MySQL in XAMPP.
4. Go to phpMyAdmin and import database/library.sql.
5. Then open the browser and go to:
http://localhost/Library/input002.php

# Project Structure

Library/
PHP source code of the system

database/
SQL file used to create the database

diagrams/
ERD and database design diagrams

docs/
Final project report

screenshots/
System interface screenshots

# System Screenshots

Screenshots of the system interfaces are available in the screenshots folder, including:
- Reader interface
- Librarian interface
- Admin interface
- SuperAdmin interface
