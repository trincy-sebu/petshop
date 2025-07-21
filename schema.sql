-- Create the users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('admin', 'staff') NOT NULL
);

-- Create the registrations table
CREATE TABLE registrations (
    RegId INT AUTO_INCREMENT PRIMARY KEY,
    RegNo VARCHAR(20) NOT NULL UNIQUE,
    owner_name VARCHAR(255) NOT NULL,
    owner_address TEXT NOT NULL,
    owner_phone VARCHAR(20) NOT NULL,
    owner_email VARCHAR(255) NOT NULL,
    pet_name VARCHAR(255) NOT NULL,
    species VARCHAR(255) NOT NULL,
    breed VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    registration_date DATE NOT NULL
);

-- Create the consultations table
CREATE TABLE consultations (
    consultation_id INT AUTO_INCREMENT PRIMARY KEY,
    RegNo VARCHAR(20) NOT NULL,
    user_id INT NOT NULL,
    consultation_date DATE NOT NULL,
    diagnosis TEXT NOT NULL,
    notes TEXT,
    FOREIGN KEY (RegNo) REFERENCES registrations(RegNo),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Create the bills table
CREATE TABLE bills (
    bill_id VARCHAR(20) NOT NULL PRIMARY KEY,
    consultation_id INT NOT NULL,
    RegNo VARCHAR(20) NOT NULL,
    bill_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('paid', 'pending', 'cancelled') NOT NULL,
    recorded_by_user_id INT NOT NULL,
    FOREIGN KEY (consultation_id) REFERENCES consultations(consultation_id),
    FOREIGN KEY (RegNo) REFERENCES registrations(RegNo),
    FOREIGN KEY (recorded_by_user_id) REFERENCES users(user_id)
);

-- Create the bill_items table
CREATE TABLE bill_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    bill_id VARCHAR(20) NOT NULL,
    item_description VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (bill_id) REFERENCES bills(bill_id)
);

-- Create the billable_items table
CREATE TABLE billable_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL UNIQUE
);
