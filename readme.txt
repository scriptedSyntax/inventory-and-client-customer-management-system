============================================
           AFRICA GRIPS - README
============================================

A simple PHP + MySQL system for managing equipment rentals,
clients, guarantors, and payments.

--------------------------------------------
🔧 SYSTEM REQUIREMENTS
--------------------------------------------
- XAMPP or WAMP installed
- PHP 7.4 or higher
- MySQL or MariaDB
- Web browser (Chrome, Firefox, etc.)

--------------------------------------------
📁 PROJECT STRUCTURE (Simplified)
--------------------------------------------

africa_grips/
│
├── index.php
├── database.sql                   -- Schema + Dummy Data
│
├── auth/        (login, logout, session control)
├── clients/     (client views & management)
├── config/      (database config & helper functions)
├── dashboards/  (admin, debts, rentals, stats)
├── equipment/   (equipment CRUD & status)
├── includes/    (headers, footers, utility PHP)
└── rentals/     (rental process and views)

--------------------------------------------
🛠️ INSTALLATION GUIDE (Localhost)
--------------------------------------------

Step 1: SETUP DATABASE
----------------------
1. Launch XAMPP or WAMP
2. Start "Apache" and "MySQL" services
3. Open your browser and visit: http://localhost/phpmyadmin
4. Create a new database:
      Database Name: africa_grips
5. Import SQL file:
      File: database.sql (includes schema + dummy data)
6. Click "Go" to finish importing.

Step 2: CONFIGURE DB CONNECTION
-------------------------------
Edit the file: config/db.php

   $host     = "localhost";
   $dbname   = "africa_grips";
   $username = "root";
   $password = ""; // default for XAMPP/WAMP

Step 3: PLACE PROJECT FILES
---------------------------
Move the entire "africa_grips" folder to:

   XAMPP:  C:\xampp\htdocs\
   WAMP:   C:\wamp64\www\

Step 4: RUN IN BROWSER
----------------------
Visit:

   http://localhost/africa_grips/

It should load the project homepage or redirect to login.

--------------------------------------------
🔐 DEFAULT ADMIN LOGIN (if dummy data used)
--------------------------------------------
| Email               | Password            |
|---------------------|---------------------|
| admin1@example.com  | admin123 (example)  |
| admin2@example.com  | admin456 (example)  |

*Note: Passwords may need to be hashed depending on your login logic.
If you use a clean schema version, insert your own admin manually.

--------------------------------------------
📋 USAGE OVERVIEW
--------------------------------------------
- Add/edit/delete clients and guarantors
- Manage equipment inventory
- Track rentals: ongoing, completed, unpaid
- Verify clients with OTP (mock/demo)
- Admin dashboard with quick stats
- Export or review client data and rental history

--------------------------------------------
💡 TROUBLESHOOTING
--------------------------------------------
- Apache won’t start?
    - Port 80 might be used by Skype or IIS
- SQL import fails?
    - Check you're using the correct SQL file and DB name
- Blank screen?
    - Enable PHP error reporting:
        Add this to the top of index.php:
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

--------------------------------------------
📌 NOTES
--------------------------------------------
- OTP verification is a placeholder (you can integrate Twilio or SMS API)
- Passwords are plain text unless hashing is applied
- Be sure to maintain DB foreign key relationships when editing

--------------------------------------------
🧑‍💻 AUTHOR / MAINTAINER
--------------------------------------------
We can enhance this project by adding:
- SMS OTP integration
- Equipment return process
- Payment gateway (e.g., MPesa, PayPal)
- Email alerts
- Rental invoice PDF export

Happy coding!

============================================
