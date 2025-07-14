# 🌍 Equipment Rental System

A **simple PHP + MySQL web app** to manage:
- ✅ Equipment rentals  
- 👤 Clients & guarantors  
- 💰 Payments  
- 📊 Dashboards & statistics  

Perfect for small-scale rental businesses needing a lightweight, offline-friendly solution.

---

## 🔧 System Requirements

- 🐘 PHP 7.4 or higher  
- 🛢️ MySQL or MariaDB  
- 💻 Web browser (Chrome, Firefox, etc.)  
- 🧰 XAMPP or WAMP  

---

## 📁 Project Structure

```
africa_grips/
├── index.php
├── database.sql              ← Database schema + demo data
│
├── auth/                     ← Login, logout, session
├── clients/                  ← Client & guarantor management
├── config/                   ← DB config + helpers
├── dashboards/               ← Admin, debts, rentals, stats
├── equipment/                ← Equipment CRUD + status
├── includes/                 ← Headers, footers, utilities
└── rentals/                  ← Rental workflows & views
```

---

## 🛠️ Installation Guide (Localhost)

### 🔹 Step 1: Setup Database

1. Start **Apache** and **MySQL** via XAMPP/WAMP  
2. Open browser and go to: `http://localhost/phpmyadmin`  
3. Create a database:  
   - **Name**: `africa_grips`  
4. Import the file `database.sql`  
5. Click **Go** ✅  

---

### 🔹 Step 2: Configure DB Connection

Edit the file: `config/db.php`

```php
$host     = "localhost";
$dbname   = "africa_grips";
$username = "root";
$password = ""; // default for XAMPP/WAMP
```

---

### 🔹 Step 3: Place Project Files

Move the entire `africa_grips/` folder to:

- XAMPP: `C:\xampp\htdocs\`  
- WAMP:  `C:\wamp64\www\`  

---

### 🔹 Step 4: Run in Browser

Visit: [http://localhost/africa_grips/](http://localhost/africa_grips/)  
It should load the homepage or redirect to login.

---

## 🔐 Default Admin Login (Demo Data)

| Email                       | Password   |
| --------------------------- | ---------- |
| admin1@example.com          | `admin123` |
| admin2@example.com          | `admin456` |

> 📝 *If using a clean database version, you'll need to insert your own admin manually.*  
> *Passwords may need to be hashed depending on your authentication logic.*

---

## 📋 Features & Usage Overview

- 👥 Add, edit, or delete clients and guarantors  
- 🧰 Equipment inventory management  
- 🔄 Track rentals: ongoing, returned, unpaid  
- 🔐 OTP-based client verification (mock/demo)  
- 📊 Admin dashboard with key stats  
- 📤 Export and review client/rental data  

---

## 📞 Interested in Using This System? Fully Customised and developed further to your needs?

If you'd like to get this system set up for your business or need help with customization or deployment, feel free to reach out.
📧 **Email Samuel:** [samuelmwas262@gmail.com](mailto:samuelmwas262@gmail.com)

---

## 💡 Troubleshooting

- ❌ **Apache won’t start?**  
  - Port 80 might be in use (Skype, IIS, etc.)

- ❌ **SQL import fails?**  
  - Ensure the correct database name and file are used

- 🧱 **Blank screen?**  
  - Enable PHP error reporting in `index.php`:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 📌 Notes

- ⚠️ OTP feature is demo only — consider integrating Twilio or another SMS API  
- ⚠️ Passwords are stored in plain text unless hashing is applied  
- ⚠️ Be cautious when editing — respect DB foreign key relationships  

---

## 🚀 Future Improvements

- 📲 SMS OTP verification  
- 🔁 Equipment return process  
- 💳 Payment gateway integration (M-Pesa, PayPal)  
- 📧 Email notifications  
- 🧾 Export rental invoices as PDFs  

---

## 👨‍💻 Author / Maintainer

This project was created by **Samuel Mwangi** to support local businesses in managing equipment rentals more efficiently.  
You're welcome to **use**, **customize**, and **extend** it to suit your needs.  
If you’d like to share improvements, feel free to **submit a pull request to the `main` branch** — contributions are always appreciated!

**Happy coding!** 💻✨
