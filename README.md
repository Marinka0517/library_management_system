# Library Management System

A database-driven web application built with **PHP and MySQL** that simulates how a real library manages books, readers, and administrative operations.

This project was developed as my Database Management Systems final project.

The goal of this project was to **design a small library system that connects a database with a web interface, and allows different user roles to manage books and accounts**.

# Key Features

The system supports multiple user roles. Each role has different permissions and functions in the system.

## Readers
- Search books
- Borrow books
- Return books
- View fine records
- Edit personal information

![Reader Dashboard](screenshots/Reader/reader_dashboard.png)

## Librarians
- Add new books
- Edit book information
- Delete books
- Borrow books for readers
- Process book returns

![Librarian Dashboard](screenshots/Librarian/librarian_dashboard.png)

## Admins
- Search books
- Maintain book records
- Manage user accounts
- Perform system maintenance

![Admin Dashboard](screenshots/Admin/admin_dashboard.png)

## SuperAdmin 
SuperAdmin has full system control.
- Manage administrator accounts
- View system statistics
- Access all system functions

![SuperAdmin Dashboard](screenshots/SuperAdmin/superadmin_dashboard.png)

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

Users interact with the web pages, PHP processes the requests, and MySQL stores the data.

# Database Design

The database was designed using:
- Entity Relationship Diagram (ERD)

![ER Diagram](diagrams/ERD.png)

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

The repository is organized into several folders to separate source code, database files, diagrams, and documentation.

## Library
PHP source code of the system

## database
SQL file used to create the database

## diagrams
ERD and database design diagrams

## docs
Final project report

## screenshots
System interface screenshots

# System Screenshots

Screenshots of the system interfaces are available in the screenshots folder, including:
- Reader interface
- Librarian interface
- Admin interface
- SuperAdmin interface
