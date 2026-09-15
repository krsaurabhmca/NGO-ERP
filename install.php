<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (file_exists(__DIR__ . '/.env')) {
    http_response_code(404);
    $errorFile = __DIR__ . '/app/views/errors/404.php';
    if (file_exists($errorFile)) {
        require $errorFile;
    } else {
        echo '<!DOCTYPE html><html><head><title>404 Not Found</title>
        <style>body{margin:0;font-family:sans-serif;background:#f4f5f7;display:flex;align-items:center;justify-content:center;min-height:100vh;}
        .box{text-align:center;padding:2rem;}h1{font-size:5rem;margin:0;color:#e2e8f0;font-weight:800;}
        p{color:#64748b;font-size:15px;margin-top:8px;}</style></head>
        <body><div class="box"><h1>404</h1><p>Page Not Found</p></div></body></html>';
    }
    exit;
}

// Fetch base URL automatically for shared hosting
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['SERVER_PORT'] ?? 0) == 443
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$protocol = $isSecure ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if (basename($path) === 'public') {
    $path = rtrim(dirname($path), '/');
}
$baseUrl = $protocol . $host . ($path !== '' ? $path : '') . '/';

$error = '';
$success = '';
$step = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step1'])) {
        $dbHost = trim($_POST['db_host'] ?? 'localhost');
        $dbName = trim($_POST['db_name'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = $_POST['db_pass'] ?? '';
        $appUrl = trim($_POST['app_url'] ?? $baseUrl);

        // Backend Validation Step 1
        if ($dbHost === '' || $dbName === '' || $dbUser === '') {
            $error = 'Please fill in Database Host, Database Name, and Username.';
            $step = 1;
        } elseif (!preg_match('/^[a-zA-Z0-9_\-]+$/', $dbName)) {
            $error = 'Database name can only contain letters, numbers, underscores, and hyphens.';
            $step = 1;
        } elseif (!filter_var($appUrl, FILTER_VALIDATE_URL)) {
            $error = 'Please enter a valid Application URL (must start with http:// or https://).';
            $step = 1;
        } else {
            try {
                // Test connection
                $dsn = "mysql:host={$dbHost};charset=utf8mb4";
                $pdo = new PDO($dsn, $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);

                // Save to session and move to step 2
                $_SESSION['install_db'] = [
                    'db_host' => $dbHost,
                    'db_name' => $dbName,
                    'db_user' => $dbUser,
                    'db_pass' => $dbPass,
                    'app_url' => rtrim($appUrl, '/') . '/'
                ];
                $step = 2;
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                if (strpos($msg, 'Access denied') !== false) {
                    $error = "Access denied for user '{$dbUser}'. Please verify your database username and password.";
                } elseif (strpos($msg, 'Connection refused') !== false || strpos($msg, 'Unknown MySQL server') !== false || strpos($msg, 'getaddrinfo') !== false) {
                    $error = "Could not connect to database host '{$dbHost}'. Ensure MySQL service is running or check hostname.";
                } else {
                    $error = 'Database connection error: ' . $msg;
                }
                $step = 1;
            } catch (Exception $e) {
                $error = 'Connection error: ' . $e->getMessage();
                $step = 1;
            }
        }
    } elseif (isset($_POST['step2'])) {
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPass = $_POST['admin_pass'] ?? '';
        $adminPassConfirm = $_POST['admin_pass_confirm'] ?? '';

        // Backend Validation Step 2
        if ($adminEmail === '' || $adminPass === '') {
            $error = 'Please provide both Admin Email and Password.';
            $step = 2;
        } elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address for the admin account.';
            $step = 2;
        } elseif (strlen($adminPass) < 8) {
            $error = 'Admin password must be at least 8 characters long for security.';
            $step = 2;
        } elseif ($adminPass !== $adminPassConfirm) {
            $error = 'Password confirmation does not match.';
            $step = 2;
        } elseif (!isset($_SESSION['install_db'])) {
            $error = 'Session expired. Please restart the database configuration.';
            $step = 1;
        } elseif (!is_writable(__DIR__)) {
            $error = 'Root directory is not writable. Cannot create .env file. Please check folder permissions.';
            $step = 2;
        } else {
            $db = $_SESSION['install_db'];
            try {
                $dsn = "mysql:host={$db['db_host']};charset=utf8mb4";
                $pdo = new PDO($dsn, $db['db_user'], $db['db_pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db['db_name']}`");
                $pdo->exec("USE `{$db['db_name']}`");

                // Run database creation
                $sql = <<<'SQL'
-- Initial Migration: Core Tables

-- Roles Table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Permissions Table
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Role Permissions (Pivot Table)
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=INNODB;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=INNODB;

-- Default Roles
INSERT IGNORE INTO roles (name, description) VALUES 
('Super Admin', 'Full system access'),
('Admin', 'Administrative access'),
('Manager', 'Management level access'),
('Accountant', 'Financial access'),
('Data Entry Operator', 'Basic data entry');


-- Seed Admin User
SET @role_id = (SELECT id FROM roles WHERE name = 'Super Admin' LIMIT 1);

INSERT IGNORE INTO users (role_id, name, email, password, status, created_at) 
VALUES (@role_id, 'Super Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW());

-- Password is 'password'


-- Settings Table Migration
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) NOT NULL UNIQUE,
    key_value TEXT,
    group_name VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Initial Settings Data
INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES 
('ngo_name', 'NGO HELP', 'organization'),
('ngo_email', 'info@ngohelp.org', 'organization'),
('ngo_phone', '+91 98765 43210', 'organization'),
('ngo_address', '123 Charity Lane, Mumbai, India', 'organization'),
('ngo_website', 'https://www.ngohelp.org', 'organization'),
('timezone', 'Asia/Kolkata', 'localization'),
(''₹'', '₹', 'localization'),
('currency_code', 'INR', 'localization'),
('smtp_host', '', 'smtp'),
('smtp_port', '587', 'smtp'),
('smtp_user', '', 'smtp'),
('smtp_pass', '', 'smtp'),
('smtp_encryption', 'tls', 'smtp');


-- Add Logo and Favicon to settings
INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES 
('ngo_logo', '', 'organization'),
('ngo_favicon', '', 'organization');


-- Add Sender Details to SMTP settings
INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES 
('smtp_from_name', 'NGO HELP', 'smtp'),
('smtp_from_email', 'noreply@ngohelp.org', 'smtp');


-- Add Payment Gateway Settings
INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES 
('razorpay_key_id', '', 'payments'),
('razorpay_key_secret', '', 'payments'),
('phonepe_merchant_id', '', 'payments'),
('phonepe_salt_key', '', 'payments'),
('phonepe_salt_index', '1', 'payments');


-- Members Table Migration
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    membership_id VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    join_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Volunteers Table Migration
CREATE TABLE IF NOT EXISTS volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    volunteer_id VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    skills TEXT,
    experience TEXT,
    status ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    join_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Donors Table Migration
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    donor_type ENUM('individual', 'corporate', 'foundation') DEFAULT 'individual',
    total_donations DECIMAL(15, 2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Beneficiaries Table Migration
CREATE TABLE IF NOT EXISTS beneficiaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    beneficiary_id VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('male', 'female', 'other', 'not_specified') DEFAULT 'not_specified',
    contact_info VARCHAR(100),
    address TEXT,
    assistance_type TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    enrolled_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Alter Beneficiaries Table: Replace age with date_of_birth
ALTER TABLE beneficiaries 
DROP COLUMN age,
ADD COLUMN date_of_birth DATE AFTER name;


-- Alter Beneficiaries Table: Add photo and documents columns
ALTER TABLE beneficiaries 
ADD COLUMN photo VARCHAR(255) AFTER gender,
ADD COLUMN documents VARCHAR(255) AFTER assistance_type;


-- CMS Tables Migration

-- Page Content Table
CREATE TABLE IF NOT EXISTS cms_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255),
    content LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Gallery/Certificates/Achievements Table
CREATE TABLE IF NOT EXISTS cms_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('gallery', 'certificate', 'achievement') NOT NULL,
    title VARCHAR(255),
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Initial Content
INSERT IGNORE INTO cms_pages (page_key, title, content) VALUES 
('about', 'About Our NGO', 'Welcome to our organization. We are dedicated to making a difference.');


-- Alter cms_pages Table: Add mission and vision columns
ALTER TABLE cms_pages 
ADD COLUMN mission TEXT AFTER title,
ADD COLUMN vision TEXT AFTER mission;

-- Update initial about page with default mission/vision
UPDATE cms_pages SET 
mission = 'To empower communities through sustainable development and education.',
vision = 'A world where every individual has the opportunity to thrive and succeed.'
WHERE page_key = 'about';


-- Alter cms_pages Table: Add image column
ALTER TABLE cms_pages 
ADD COLUMN image VARCHAR(255) AFTER title;


-- Add 'slider' to cms_media category ENUM
ALTER TABLE cms_media MODIFY COLUMN category ENUM('gallery', 'certificate', 'achievement', 'slider') NOT NULL;

-- Add status column to cms_media
ALTER TABLE cms_media ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER file_path;

-- Drop Volunteers Table
DROP TABLE IF EXISTS volunteers;


-- Add more fields to members table
ALTER TABLE members 
ADD COLUMN gender VARCHAR(20) AFTER phone,
ADD COLUMN dob DATE AFTER gender,
ADD COLUMN blood_group VARCHAR(10) AFTER dob,
ADD COLUMN occupation VARCHAR(100) AFTER blood_group,
ADD COLUMN membership_type VARCHAR(50) DEFAULT 'Regular' AFTER occupation,
ADD COLUMN image VARCHAR(255) AFTER membership_type;


-- Add pending status to members table
ALTER TABLE members MODIFY COLUMN status ENUM('active', 'inactive', 'pending') DEFAULT 'pending';


-- Create designations table
CREATE TABLE IF NOT EXISTS designations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Add designation_id to members table
ALTER TABLE members ADD COLUMN designation_id INT AFTER image;
ALTER TABLE members ADD CONSTRAINT fk_member_designation FOREIGN KEY (designation_id) REFERENCES designations(id) ON DELETE SET NULL;


-- Add monthly_amount to designations table
ALTER TABLE designations ADD COLUMN monthly_amount DECIMAL(10, 2) DEFAULT 0.00 AFTER description;


-- Add show_in_form to designations table
ALTER TABLE designations ADD COLUMN show_in_form TINYINT(1) DEFAULT 1 AFTER description;


-- Remove description from designations table
ALTER TABLE designations DROP COLUMN description;


-- Fix group_name for branding assets
UPDATE settings SET group_name = 'organization' WHERE key_name IN ('ngo_logo', 'ngo_favicon', 'ngo_signature');

-- Ensure they exist if not already
INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES 
('ngo_logo', '', 'organization'),
('ngo_favicon', '', 'organization'),
('ngo_signature', '', 'organization');


-- Create News Table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Add core values columns to cms_pages table
ALTER TABLE cms_pages 
ADD COLUMN value_integrity_title VARCHAR(255) DEFAULT 'Integrity',
ADD COLUMN value_integrity_desc TEXT,
ADD COLUMN value_compassion_title VARCHAR(255) DEFAULT 'Compassion',
ADD COLUMN value_compassion_desc TEXT,
ADD COLUMN value_innovation_title VARCHAR(255) DEFAULT 'Innovation',
ADD COLUMN value_innovation_desc TEXT,
ADD COLUMN value_collaboration_title VARCHAR(255) DEFAULT 'Collaboration',
ADD COLUMN value_collaboration_desc TEXT;


-- Add icon columns to cms_pages table for core values
ALTER TABLE cms_pages 
ADD COLUMN value_integrity_icon VARCHAR(100) DEFAULT 'fas fa-shield-alt',
ADD COLUMN value_compassion_icon VARCHAR(100) DEFAULT 'fas fa-heart',
ADD COLUMN value_innovation_icon VARCHAR(100) DEFAULT 'fas fa-lightbulb',
ADD COLUMN value_collaboration_icon VARCHAR(100) DEFAULT 'fas fa-users';


-- Add image columns to cms_pages table for core values logos
ALTER TABLE cms_pages 
ADD COLUMN value_integrity_image VARCHAR(255),
ADD COLUMN value_compassion_image VARCHAR(255),
ADD COLUMN value_innovation_image VARCHAR(255),
ADD COLUMN value_collaboration_image VARCHAR(255);


-- Create Contacts Table to store inquiries
CREATE TABLE IF NOT EXISTS contact_inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Add settings for Contact Page management
INSERT IGNORE INTO settings (group_name, key_name, key_value) VALUES 
('contact_page', 'contact_page_status', 'active'),
('contact_page', 'show_google_map', 'yes');


-- Add social links and map settings
INSERT IGNORE INTO settings (group_name, key_name, key_value) VALUES 
('organization', 'social_facebook', ''),
('organization', 'social_twitter', ''),
('organization', 'social_instagram', ''),
('organization', 'social_linkedin', ''),
('organization', 'social_youtube', ''),
('organization', 'ngo_map_embed', '');


-- Add visibility toggles for social links
INSERT IGNORE INTO settings (group_name, key_name, key_value) VALUES 
('organization', 'show_social_facebook', '1'),
('organization', 'show_social_twitter', '1'),
('organization', 'show_social_instagram', '1'),
('organization', 'show_social_linkedin', '1'),
('organization', 'show_social_youtube', '1');


-- Create Projects Table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description LONGTEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('ongoing', 'completed', 'planned') DEFAULT 'ongoing',
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Create Careers Table
CREATE TABLE IF NOT EXISTS careers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description LONGTEXT NOT NULL,
    location VARCHAR(255),
    job_type VARCHAR(100),
    status ENUM('open', 'closed') DEFAULT 'open',
    deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Add manager and budget to projects table
ALTER TABLE projects
ADD COLUMN manager VARCHAR(255) AFTER title,
ADD COLUMN budget DECIMAL(15, 2) DEFAULT 0.00 AFTER description;


-- Create Project Gallery Table
CREATE TABLE IF NOT EXISTS project_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=INNODB;


-- Remove manager and budget from projects table
ALTER TABLE projects
DROP COLUMN manager,
DROP COLUMN budget;


-- Add video_url to projects table
ALTER TABLE projects
ADD COLUMN video_url VARCHAR(255) AFTER image;


-- Create Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description LONGTEXT NOT NULL,
    image VARCHAR(255),
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


-- Drop Events Table
DROP TABLE IF EXISTS events;


-- Create Job Applications Table
CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    career_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    cover_letter TEXT,
    resume VARCHAR(255),
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'hired') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (career_id) REFERENCES careers(id) ON DELETE CASCADE
) ENGINE=INNODB;


-- Add separate address fields to job_applications
ALTER TABLE job_applications
ADD COLUMN district VARCHAR(100) AFTER address,
ADD COLUMN city VARCHAR(100) AFTER district,
ADD COLUMN pincode VARCHAR(20) AFTER city,
ADD COLUMN state VARCHAR(100) AFTER pincode;


-- Add called_for_interview status to job_applications
ALTER TABLE job_applications MODIFY COLUMN status ENUM('pending', 'reviewed', 'shortlisted', 'called_for_interview', 'rejected', 'hired') DEFAULT 'pending';


-- Create Donations Table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(150) NOT NULL,
    donor_email VARCHAR(100),
    donor_phone VARCHAR(20),
    amount DECIMAL(12, 2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'offline',
    transaction_id VARCHAR(100),
    message TEXT,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    goal_amount DECIMAL(12, 2) NOT NULL DEFAULT 0,
    raised_amount DECIMAL(12, 2) NOT NULL DEFAULT 0,
    start_date DATE,
    end_date DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;


ALTER TABLE donations ADD COLUMN campaign_id INT NULL AFTER id;


CREATE TABLE IF NOT EXISTS assistance_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

INSERT IGNORE INTO assistance_types (name) VALUES
('Education'),
('Medical'),
('Food'),
('Housing'),
('Employment'),
('Legal Aid'),
('Elderly Care'),
('Child Welfare');


INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES
('id_prefix_member', 'MEM', 'organization'),
('id_prefix_donor', 'DNR', 'organization'),
('id_prefix_beneficiary', 'BEN', 'organization'),
('id_prefix_project', 'PROJ', 'organization');



-- Add district column to beneficiaries for district-wise impact analysis
ALTER TABLE beneficiaries ADD COLUMN district VARCHAR(100) AFTER address;
UPDATE beneficiaries SET district = 'Unknown' WHERE district IS NULL OR district = '';

-- Success Stories Table
CREATE TABLE IF NOT EXISTS success_stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    beneficiary_id INT,
    title VARCHAR(255) NOT NULL,
    story TEXT NOT NULL,
    image VARCHAR(255),
    status ENUM('published', 'draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries(id) ON DELETE SET NULL
) ENGINE=INNODB;

-- Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    designation VARCHAR(255),
    content TEXT NOT NULL,
    image VARCHAR(255),
    rating TINYINT DEFAULT 5,
    status ENUM('published', 'draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Add Duration and Stipend fields for intern management
ALTER TABLE job_applications ADD COLUMN duration VARCHAR(50) DEFAULT NULL AFTER resume;
ALTER TABLE job_applications ADD COLUMN stipend VARCHAR(50) DEFAULT NULL AFTER duration;
ALTER TABLE job_applications ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL AFTER stipend;
ALTER TABLE job_applications ADD COLUMN offer_letter VARCHAR(255) DEFAULT NULL AFTER profile_image;
ALTER TABLE job_applications ADD COLUMN internship_status ENUM('active', 'completed') DEFAULT 'active' AFTER offer_letter;
ALTER TABLE job_applications ADD COLUMN completion_date DATE DEFAULT NULL AFTER internship_status;

INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES
('template_certificate', 'This is to certify that\n{name}\nhas successfully completed the internship program\nfor the position of {position} at\n{ngo_name}\nDuration: {duration}', 'template'),
('template_offer_letter', 'Dear {name},\n\nWe are pleased to offer you the position of {position} at {ngo_name}. After reviewing your qualifications and interview performance, we are confident that you will make a valuable contribution to our team.\n\nThe details of your internship offer are as follows:\n\nPosition: {position}\nDuration: {duration}\nStipend: {stipend}\nReporting: Head Office\nStart Date: {date}\n\nPlease confirm your acceptance of this offer by replying to this email within 5 business days. We look forward to welcoming you to our team and providing you with a rewarding internship experience.\n\nIf you have any questions, please do not hesitate to contact us.\n\nWe wish you a fulfilling journey with us.\n\nSincerely,\n{ngo_name}', 'template'),
('template_email_acceptance', '<p>Dear <strong>{name}</strong>,</p><p>Congratulations! We are pleased to inform you that your application for <strong>{position}</strong> has been accepted.</p>', 'template'),
('template_email_rejection', '<p>Dear <strong>{name}</strong>,</p><p>Thank you for your interest in <strong>{position}</strong>. After careful review, we regret to inform you... </p>', 'template');

ALTER TABLE donations ADD COLUMN donor_dob DATE DEFAULT NULL AFTER donor_phone;
ALTER TABLE donations ADD COLUMN donor_address TEXT DEFAULT NULL AFTER donor_dob;

INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES ('phonepe_environment', 'sandbox', 'payments');
INSERT IGNORE INTO settings (key_name, key_value, group_name) VALUES ('active_online_gateway', '', 'payments');

CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    category VARCHAR(100) DEFAULT 'General',
    expense_date DATE NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cash',
    receipt VARCHAR(255) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS expense_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

INSERT IGNORE INTO expense_categories (name) VALUES
('General'),
('Office Supplies'),
('Utilities'),
('Salaries'),
('Travel'),
('Events'),
('Maintenance'),
('Marketing'),
('Other');

-- Update known offline donations to specific methods
UPDATE donations SET payment_method = 'cash' WHERE payment_method = 'offline' AND message LIKE '%cash%';
UPDATE donations SET payment_method = 'bank_transfer' WHERE payment_method = 'offline' AND message LIKE '%bank%';




-- Revert demo donations with new payment methods back to 'offline'
UPDATE donations SET payment_method = 'offline' WHERE payment_method IN ('upi', 'card', 'bank_transfer', 'cash');


-- User Permissions Table (individual user-level permissions override)
CREATE TABLE IF NOT EXISTS user_permissions (
    user_id INT,
    permission_id INT,
    PRIMARY KEY (user_id, permission_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=INNODB;


-- Partners Table
CREATE TABLE IF NOT EXISTS partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(500) DEFAULT NULL,
    website VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;


ALTER TABLE donations ADD COLUMN donor_pan VARCHAR(10) DEFAULT NULL AFTER donor_dob;
ALTER TABLE donations ADD COLUMN donor_city VARCHAR(100) DEFAULT NULL AFTER donor_address;
ALTER TABLE donations ADD COLUMN donor_state VARCHAR(100) DEFAULT NULL AFTER donor_city;
ALTER TABLE donations ADD COLUMN donor_pincode VARCHAR(10) DEFAULT NULL AFTER donor_state;


ALTER TABLE donations ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_donations_uuid (uuid);
ALTER TABLE users ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_users_uuid (uuid);
ALTER TABLE members ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_members_uuid (uuid);
ALTER TABLE donors ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_donors_uuid (uuid);
ALTER TABLE beneficiaries ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_beneficiaries_uuid (uuid);
ALTER TABLE campaigns ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_campaigns_uuid (uuid);
ALTER TABLE projects ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_projects_uuid (uuid);
ALTER TABLE news ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_news_uuid (uuid);
ALTER TABLE careers ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_careers_uuid (uuid);
ALTER TABLE job_applications ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_job_applications_uuid (uuid);
ALTER TABLE contact_inquiries ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_contact_inquiries_uuid (uuid);
ALTER TABLE partners ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_partners_uuid (uuid);
ALTER TABLE expenses ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_expenses_uuid (uuid);
ALTER TABLE cms_pages ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_cms_pages_uuid (uuid);
ALTER TABLE cms_media ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_cms_media_uuid (uuid);
ALTER TABLE designations ADD COLUMN uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE INDEX idx_designations_uuid (uuid);

-- Backfill UUIDs for existing records
UPDATE donations SET uuid = UUID() WHERE uuid IS NULL;
UPDATE users SET uuid = UUID() WHERE uuid IS NULL;
UPDATE members SET uuid = UUID() WHERE uuid IS NULL;
UPDATE donors SET uuid = UUID() WHERE uuid IS NULL;
UPDATE beneficiaries SET uuid = UUID() WHERE uuid IS NULL;
UPDATE campaigns SET uuid = UUID() WHERE uuid IS NULL;
UPDATE projects SET uuid = UUID() WHERE uuid IS NULL;
UPDATE news SET uuid = UUID() WHERE uuid IS NULL;
UPDATE careers SET uuid = UUID() WHERE uuid IS NULL;
UPDATE job_applications SET uuid = UUID() WHERE uuid IS NULL;
UPDATE contact_inquiries SET uuid = UUID() WHERE uuid IS NULL;
UPDATE partners SET uuid = UUID() WHERE uuid IS NULL;
UPDATE expenses SET uuid = UUID() WHERE uuid IS NULL;
UPDATE cms_pages SET uuid = UUID() WHERE uuid IS NULL;
UPDATE cms_media SET uuid = UUID() WHERE uuid IS NULL;
UPDATE designations SET uuid = UUID() WHERE uuid IS NULL;


-- Add structured address fields to members table
ALTER TABLE members
ADD COLUMN address_line TEXT AFTER address,
ADD COLUMN city VARCHAR(100) AFTER address_line,
ADD COLUMN district VARCHAR(100) AFTER city,
ADD COLUMN state VARCHAR(100) AFTER district,
ADD COLUMN pin VARCHAR(20) AFTER state;


-- Add password column to members table for member login
ALTER TABLE members ADD COLUMN password VARCHAR(255) AFTER image;


-- Add password_plain column to members table for admin viewing
ALTER TABLE members ADD COLUMN password_plain VARCHAR(255) AFTER password;


-- Add member donation fields to donations table
ALTER TABLE donations 
ADD COLUMN member_id INT NULL AFTER campaign_id,
ADD COLUMN is_recurring TINYINT(1) DEFAULT 0 AFTER status,
ADD COLUMN recurring_frequency ENUM('monthly') NULL AFTER is_recurring;

ALTER TABLE donations ADD INDEX idx_donations_member_id (member_id);


-- Add payment_for_date column to track which month the payment is for
ALTER TABLE donations ADD COLUMN payment_for_date DATE NULL AFTER recurring_frequency;

UPDATE donations SET payment_for_date = DATE(created_at) WHERE payment_for_date IS NULL;


CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    member_id INT DEFAULT NULL,
    action VARCHAR(50) NOT NULL,
    module VARCHAR(50) NOT NULL,
    record_id INT DEFAULT NULL,
    description TEXT,
    old_data JSON DEFAULT NULL,
    new_data JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_member_id (member_id),
    INDEX idx_action (action),
    INDEX idx_module (module),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Create system_config table for storing application-wide configuration
-- This table is used for storing sensitive configuration like signing secrets
CREATE TABLE IF NOT EXISTS system_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) NOT NULL UNIQUE,
    config_value TEXT NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_config_key (config_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Add created_by, approved_by, and approved_at columns to donations
ALTER TABLE donations 
    ADD COLUMN created_by INT DEFAULT NULL AFTER status,
    ADD COLUMN approved_by INT DEFAULT NULL AFTER created_by,
    ADD COLUMN approved_at TIMESTAMP NULL DEFAULT NULL AFTER approved_by,
    ADD INDEX idx_created_by (created_by),
    ADD INDEX idx_approved_by (approved_by),
    ADD CONSTRAINT fk_donations_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_donations_approved_by FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL;


CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_ip (ip_address),
    INDEX idx_attempted_at (attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE members
    ADD COLUMN email_verification_token VARCHAR(64) NULL AFTER status,
    ADD COLUMN email_verified_at DATETIME NULL AFTER email_verification_token;


CREATE TABLE IF NOT EXISTS registration_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    email VARCHAR(100) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_email (email),
    INDEX idx_attempted_at (attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE success_stories ADD COLUMN uuid VARCHAR(36) DEFAULT NULL AFTER id;
UPDATE success_stories SET uuid = UUID() WHERE uuid IS NULL;

ALTER TABLE testimonials ADD COLUMN uuid VARCHAR(36) DEFAULT NULL AFTER id;
UPDATE testimonials SET uuid = UUID() WHERE uuid IS NULL;

ALTER TABLE assistance_types ADD COLUMN uuid VARCHAR(36) DEFAULT NULL AFTER id;
UPDATE assistance_types SET uuid = UUID() WHERE uuid IS NULL;

ALTER TABLE expense_categories ADD COLUMN uuid VARCHAR(36) DEFAULT NULL AFTER id;
UPDATE expense_categories SET uuid = UUID() WHERE uuid IS NULL;

ALTER TABLE project_gallery ADD COLUMN uuid VARCHAR(36) DEFAULT NULL AFTER id;
UPDATE project_gallery SET uuid = UUID() WHERE uuid IS NULL;


ALTER TABLE audit_logs MODIFY COLUMN record_id VARCHAR(36) DEFAULT NULL;


-- Students Table Migration
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mobile_no VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;
SQL;
                $pdo->exec($sql);

                // Update Admin credentials
                $hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET email = ?, password = ? WHERE id = 1")->execute([$adminEmail, $hashedPass]);

                // Create .env file
                $appKey = bin2hex(random_bytes(16));
                $envContent = "APP_NAME=\"NGO Management System\"\n";
                $envContent .= "APP_URL={$db['app_url']}\n";
                $envContent .= "APP_KEY={$appKey}\n\n";
                $envContent .= "DB_HOST={$db['db_host']}\n";
                $envContent .= "DB_NAME={$db['db_name']}\n";
                $envContent .= "DB_USER={$db['db_user']}\n";
                $envContent .= "DB_PASS={$db['db_pass']}\n";

                $envPath = __DIR__ . '/.env';
                $written = file_put_contents($envPath, $envContent);
                if ($written === false) {
                    throw new Exception(
                        'Could not write .env file. Please check that the folder "'
                        . __DIR__ . '" is writable (chmod 755 or 775 on Linux/cPanel).'
                    );
                }

                $success = "System setup completed successfully. You may now log in to the admin dashboard.";
                $baseUrl = $db['app_url'];
                unset($_SESSION['install_db']);
                $step = 3;

            } catch (Exception $e) {
                $error = 'Installation Failed: ' . $e->getMessage();
                $step = 2;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Management System — Setup</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f4f5f7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            padding: 40px 16px;
        }

        .setup-container {
            max-width: 480px;
            margin: 0 auto;
        }

        .setup-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 32px;
        }

        .setup-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .setup-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .setup-subtitle {
            font-size: 13px;
            color: #64748b;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-error {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .alert-info {
            background-color: #f0f9ff;
            border-color: #bae6fd;
            color: #0369a1;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-row {
            display: flex;
            gap: 12px;
        }

        .form-row .form-group {
            flex: 1;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="url"] {
            width: 100%;
            padding: 9px 12px;
            font-size: 14px;
            color: #0f172a;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        input.input-error {
            border-color: #ef4444;
        }

        .field-error {
            display: none;
            font-size: 12px;
            color: #ef4444;
            margin-top: 4px;
        }

        .form-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.15s, border-color 0.15s;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #1e293b;
            color: #ffffff;
            border-color: #1e293b;
        }

        .btn-primary:hover {
            background-color: #0f172a;
        }

        .btn-outline {
            background-color: #ffffff;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-outline:hover {
            background-color: #f8fafc;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .password-toggle-wrapper {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 12px;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
        }

        .password-toggle-btn:hover {
            color: #0f172a;
        }
    </style>
</head>

<body>
    <div class="setup-container">
        <div class="setup-card">
            <div class="setup-header">
                <h1 class="setup-title">Setup & Configuration</h1>
                <p class="setup-subtitle">
                    <?php
                    if ($step === 1)
                        echo "Step 1 of 2 — Database Connection";
                    elseif ($step === 2)
                        echo "Step 2 of 2 — Administrator Account";
                    else
                        echo "Installation Complete";
                    ?>
                </p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($step === 3 && $success !== ''): ?>
                <div class="alert alert-success">
                    <strong>Setup Complete!</strong><br>
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <div class="btn-group">
                    <a href="<?php echo htmlspecialchars($baseUrl); ?>" class="btn btn-outline">Visit Site</a>
                    <a href="<?php echo htmlspecialchars($baseUrl); ?>auth" class="btn btn-primary">Admin Login</a>
                </div>
            <?php elseif ($step === 1): ?>
                <form method="POST" action="" id="step1Form" novalidate>
                    <input type="hidden" name="step1" value="1">

                    <div class="form-group">
                        <label for="app_url">Application URL</label>
                        <input type="url" name="app_url" id="app_url" value="<?php echo htmlspecialchars($baseUrl); ?>"
                            required>
                        <div class="field-error" id="err_app_url">Enter a valid URL starting with http:// or https://</div>
                        <div class="form-text">Base web address where the application is accessible.</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="db_host">Database Host</label>
                            <input type="text" name="db_host" id="db_host" value="localhost" required>
                            <div class="field-error" id="err_db_host">Host is required</div>
                        </div>
                        <div class="form-group">
                            <label for="db_name">Database Name</label>
                            <input type="text" name="db_name" id="db_name" placeholder="e.g. ngo_db" required>
                            <div class="field-error" id="err_db_name">Enter a valid database name</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="db_user">Username</label>
                            <input type="text" name="db_user" id="db_user" placeholder="root" required>
                            <div class="field-error" id="err_db_user">Username is required</div>
                        </div>
                        <div class="form-group">
                            <label for="db_pass">Password</label>
                            <div class="password-toggle-wrapper">
                                <input type="password" name="db_pass" id="db_pass" style="padding-right: 50px;"
                                    placeholder="********">
                                <button type="button" class="password-toggle-btn"
                                    onclick="togglePass('db_pass', this)">Show</button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>
                </form>

            <?php elseif ($step === 2): ?>
                <div class="alert alert-info">
                    Connected to database
                    <strong><?php echo htmlspecialchars($_SESSION['install_db']['db_name'] ?? ''); ?></strong>. Set up your
                    administrator credentials below.
                </div>

                <form method="POST" action="" id="step2Form" novalidate>
                    <input type="hidden" name="step2" value="1">

                    <div class="form-group">
                        <label for="admin_email">Administrator Email</label>
                        <input type="email" name="admin_email" id="admin_email" placeholder="admin@example.com" required>
                        <div class="field-error" id="err_admin_email">Enter a valid email address</div>
                    </div>

                    <div class="form-group">
                        <label for="admin_pass">Password</label>
                        <div class="password-toggle-wrapper">
                            <input type="password" name="admin_pass" id="admin_pass" style="padding-right: 50px;" required
                                minlength="8" placeholder="********">
                            <button type="button" class="password-toggle-btn"
                                onclick="togglePass('admin_pass', this)">Show</button>
                        </div>
                        <div class="field-error" id="err_admin_pass">Password must be at least 8 characters</div>
                        <div class="form-text">Minimum 8 characters.</div>
                    </div>

                    <div class="form-group">
                        <label for="admin_pass_confirm">Confirm Password</label>
                        <div class="password-toggle-wrapper">
                            <input type="password" name="admin_pass_confirm" id="admin_pass_confirm"
                                style="padding-right: 50px;" required placeholder="********">
                            <button type="button" class="password-toggle-btn"
                                onclick="togglePass('admin_pass_confirm', this)">Show</button>
                        </div>
                        <div class="field-error" id="err_admin_pass_confirm">Passwords do not match</div>
                    </div>

                    <div style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary">Complete Setup</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePass(id, btn) {
            var el = document.getElementById(id);
            if (!el) return;
            if (el.type === 'password') {
                el.type = 'text';
                btn.textContent = 'Hide';
            } else {
                el.type = 'password';
                btn.textContent = 'Show';
            }
        }

        function showError(fieldId, errorId) {
            var field = document.getElementById(fieldId);
            var err = document.getElementById(errorId);
            if (field) field.classList.add('input-error');
            if (err) err.style.display = 'block';
        }

        function clearErrors() {
            var inputs = document.querySelectorAll('input');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].classList.remove('input-error');
            }
            var errs = document.querySelectorAll('.field-error');
            for (var j = 0; j < errs.length; j++) {
                errs[j].style.display = 'none';
            }
        }

        var s1 = document.getElementById('step1Form');
        if (s1) {
            s1.addEventListener('submit', function (e) {
                clearErrors();
                var valid = true;
                var appUrl = document.getElementById('app_url').value.trim();
                var dbHost = document.getElementById('db_host').value.trim();
                var dbName = document.getElementById('db_name').value.trim();
                var dbUser = document.getElementById('db_user').value.trim();

                if (appUrl === '' || (!appUrl.startsWith('http://') && !appUrl.startsWith('https://'))) {
                    showError('app_url', 'err_app_url');
                    valid = false;
                }
                if (dbHost === '') {
                    showError('db_host', 'err_db_host');
                    valid = false;
                }
                if (dbName === '' || !/^[a-zA-Z0-9_\-]+$/.test(dbName)) {
                    showError('db_name', 'err_db_name');
                    valid = false;
                }
                if (dbUser === '') {
                    showError('db_user', 'err_db_user');
                    valid = false;
                }

                if (!valid) e.preventDefault();
            });
        }

        var s2 = document.getElementById('step2Form');
        if (s2) {
            s2.addEventListener('submit', function (e) {
                clearErrors();
                var valid = true;
                var email = document.getElementById('admin_email').value.trim();
                var pass = document.getElementById('admin_pass').value;
                var conf = document.getElementById('admin_pass_confirm').value;

                var emailReg = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '' || !emailReg.test(email)) {
                    showError('admin_email', 'err_admin_email');
                    valid = false;
                }
                if (pass.length < 8) {
                    showError('admin_pass', 'err_admin_pass');
                    valid = false;
                }
                if (pass !== conf) {
                    showError('admin_pass_confirm', 'err_admin_pass_confirm');
                    valid = false;
                }

                if (!valid) e.preventDefault();
            });
        }
    </script>
</body>

</html>