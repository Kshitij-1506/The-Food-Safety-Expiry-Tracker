# Food Safety & Expiry Tracker

## Overview

Food Safety & Expiry Tracker is a web-based database management system designed to monitor food products, batches, suppliers, and inspections. The system helps organizations track product expiry dates, manage inspections, monitor food safety compliance, and maintain accurate records through a role-based access control system.

The application provides real-time tracking of food batches, inspection results, supplier information, and expiry status through an interactive dashboard and analytics.

---

## Features

### Dashboard

* Total Products Overview
* Expiring Soon Products Count
* Failed Inspections Count
* Inspection Analytics Chart
* Batch Expiry Monitoring Table
* Search and Filter Functionality

### Product Management

* Add Products
* Update Product Information
* Delete Products
* View Product Details

### Batch Management

* Add New Batches
* Update Batch Information
* Delete Batches
* Track Manufacturing and Expiry Dates
* Monitor Inspection Status

### Inspection Management

* Record Product Inspections
* Update Inspection Reports
* Delete Inspection Records
* Store Inspection Remarks
* Pass/Fail Status Tracking

### Supplier Management

* Add Suppliers
* Update Supplier Information
* Delete Suppliers
* Manage Product-Supplier Relationships

### User Management

* User Registration and Login
* Role-Based Access Control
* Update User Roles
* Delete Users

---

## User Roles

### Developer

* Full system access
* Manage users and roles
* Add, update, delete products
* Add, update, delete batches
* Add, update, delete inspections
* Add, update, delete suppliers

### Admin

* Manage products
* Manage batches
* Manage suppliers
* View inspection data
* Cannot perform inspections

### Inspector

* View products and batches
* Add inspections
* Update inspections
* Delete inspections

### Viewer

* Read-only access
* View products, batches, suppliers, and inspections
* Cannot modify any data

---

## Database Concepts Implemented

* Primary Keys and Foreign Keys
* Entity Relationships
* Joins and Subqueries
* Stored Procedures
* Triggers
* Constraints
* Indexing
* Role-Based Access Control
* Data Validation
* Prepared Statements

---

## Technologies Used

### Frontend

* HTML5
* CSS3
* JavaScript
* Chart.js

### Backend

* PHP

### Database

* MySQL

### Development Tools

* MySQL Workbench
* VS Code
* Git & GitHub

---

## Database Structure

### Tables

#### Users

Stores user account information and roles.

#### Suppliers

Stores supplier details.

#### Products

Stores product information linked to suppliers.

#### Batches

Stores batch details including manufacturing and expiry dates.

#### Inspections

Stores inspection records and inspection status.

---

## Entity Relationships

* One Supplier can supply multiple Products.
* One Product can have multiple Batches.
* One Batch can have multiple Inspections.
* Users access the system based on assigned roles.

---

## Stored Procedures

### AddBatch

Used to insert a new batch into the database.

---

## Triggers

### Inspection Status Trigger

Automatically updates the latest inspection status of a batch whenever inspection data is inserted or modified.

---

## Installation

### Database Setup

1. Create a MySQL database:

```sql
CREATE DATABASE food_safety_db;
```

2. Import the SQL schema file.

3. Update database credentials in:

```php
db_connect.php
```

Example:

```php
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "food_safety_db";
```

### Run PHP Server

```bash
php -S localhost:8000
```

Open:

```
http://localhost:8000
```

---

## Project Workflow

1. User logs into the system.
2. Products and suppliers are managed.
3. Batches are created for products.
4. Inspectors perform inspections.
5. Dashboard displays analytics and expiry tracking.
6. System automatically updates inspection status using triggers.

---

## Future Enhancements

* Email Notifications for Expiring Products
* Barcode/QR Code Integration
* Audit Logs
* Cloud Database Deployment
* Advanced Analytics Dashboard
* Mobile Application Support
