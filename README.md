# Car Rental System

**Authors:**  
- Sama Ehab Ibrahim Adam – 7975  
- Marie Magdi Sharl – 8049  
- Kiria Elkess Daoud Botros – 8301  
- Karen Sameh Sabry Nasr – 8366  

A complete web-based Car Rental System built with **HTML**, **PHP** and **MySQL**, providing separate **Admin** and **Customer** interfaces to manage car rentals, reservations, and payments. The system includes user authentication, dashboards, search functionality, reservation, and payment processing.

---
## Introduction

This Car Rental System simplifies car rental operations, providing:

- Admin login system to manage cars, offices, reservations, and reports.  
- Customer registration, login, and reservation system.  
- Secure payment handling with hashed card information.  
- Frontend designed with HTML, CSS, and responsive layouts.  
- Backend powered by PHP and MySQL with prepared statements for security.  

---

## Environment Setup

### 1. Install XAMPP
1. Download **XAMPP Control Panel** from [https://www.apachefriends.org](https://www.apachefriends.org).  
2. Install and open XAMPP.  
3. Start **Apache** and **MySQL** (both must be running).  

### 2. Set up the Database
1. Click **Admin** next to MySQL in XAMPP Control Panel to open **phpMyAdmin**.  
2. Click **Add New Database** and create a database (e.g., `CarRentalSystem`).  
3. Go to the **SQL** tab.  
4. Paste the contents of `ddl.txt` to create tables.  
5. Paste the contents of `dml.txt` to insert sample data.  

### 3. Run the Project
1. Open the folder where XAMPP is installed.  
   - Example: `C:\xampp`  
2. Open the **htdocs** folder.  
   - Example: `C:\xampp\htdocs`  
3. Copy the `Car_rental_system` project folder into **htdocs**.  
4. Open your browser and go to:  
http://localhost/Car_rental_system/frontend/welcome.html
5. The project should now be running.  

