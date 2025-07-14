-- ====================================
-- DROP AND CREATE TABLES
-- ====================================

-- 1. Guarantors
DROP TABLE IF EXISTS rentals;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS equipment;
DROP TABLE IF EXISTS guarantors;
DROP TABLE IF EXISTS admins;

CREATE TABLE guarantors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    id_number VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    otp_code VARCHAR(10),
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id VARCHAR(100) NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    client_phone VARCHAR(20) NOT NULL,
    guarantor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guarantor_id) REFERENCES guarantors(id)
);

-- 3. Equipment
CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    daily_rate DECIMAL(10, 2) NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category VARCHAR(100),
    status VARCHAR(20) DEFAULT 'available'
);

-- 4. Rentals
CREATE TABLE rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    equipment_id INT NOT NULL,
    rental_date DATE NOT NULL,
    return_date DATE,
    otp_verified BOOLEAN DEFAULT FALSE,
    payment_status ENUM('Paid', 'To Be Paid') DEFAULT 'To Be Paid',
    amount_due DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

-- 5. Admin Users
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(191) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ====================================
-- INSERT DUMMY DATA
-- ====================================

-- Guarantors (20)
INSERT INTO guarantors (name, id_number, phone_number, otp_code, verified)
VALUES
('John Doe', 'ID1001', '0700000001', '1234', TRUE),
('Jane Smith', 'ID1002', '0700000002', '5678', TRUE),
('Alice Johnson', 'ID1003', '0700000003', '9101', FALSE),
('Bob Brown', 'ID1004', '0700000004', NULL, FALSE),
('Charlie White', 'ID1005', '0700000005', '4321', TRUE),
('Ella Black', 'ID1006', '0700000006', '8765', TRUE),
('George Green', 'ID1007', '0700000007', NULL, FALSE),
('Helen Blue', 'ID1008', '0700000008', '1111', TRUE),
('Ian Gray', 'ID1009', '0700000009', NULL, FALSE),
('Julia Pink', 'ID1010', '0700000010', '2222', TRUE),
('Kevin Silver', 'ID1011', '0700000011', '3333', TRUE),
('Laura Gold', 'ID1012', '0700000012', '4444', FALSE),
('Mark Violet', 'ID1013', '0700000013', NULL, FALSE),
('Nina Orange', 'ID1014', '0700000014', '5555', TRUE),
('Oscar Red', 'ID1015', '0700000015', '6666', TRUE),
('Paula Teal', 'ID1016', '0700000016', NULL, FALSE),
('Quincy Lime', 'ID1017', '0700000017', NULL, FALSE),
('Rita Navy', 'ID1018', '0700000018', '7777', TRUE),
('Steve Cyan', 'ID1019', '0700000019', '8888', FALSE),
('Tina Coral', 'ID1020', '0700000020', '9999', TRUE);

-- Clients (30)
INSERT INTO clients (client_id, client_name, client_phone, guarantor_id)
VALUES
('CL001', 'Client A', '0711000001', 1),
('CL002', 'Client B', '0711000002', 2),
('CL003', 'Client C', '0711000003', 3),
('CL004', 'Client D', '0711000004', 4),
('CL005', 'Client E', '0711000005', 5),
('CL006', 'Client F', '0711000006', 6),
('CL007', 'Client G', '0711000007', 7),
('CL008', 'Client H', '0711000008', 8),
('CL009', 'Client I', '0711000009', 9),
('CL010', 'Client J', '0711000010', 10),
('CL011', 'Client K', '0711000011', 11),
('CL012', 'Client L', '0711000012', 12),
('CL013', 'Client M', '0711000013', 13),
('CL014', 'Client N', '0711000014', 14),
('CL015', 'Client O', '0711000015', 15),
('CL016', 'Client P', '0711000016', 16),
('CL017', 'Client Q', '0711000017', 17),
('CL018', 'Client R', '0711000018', 18),
('CL019', 'Client S', '0711000019', 19),
('CL020', 'Client T', '0711000020', 20),
('CL021', 'Client U', '0711000021', 1),
('CL022', 'Client V', '0711000022', 2),
('CL023', 'Client W', '0711000023', 3),
('CL024', 'Client X', '0711000024', 4),
('CL025', 'Client Y', '0711000025', 5),
('CL026', 'Client Z', '0711000026', 6),
('CL027', 'Client AA', '0711000027', 7),
('CL028', 'Client AB', '0711000028', 8),
('CL029', 'Client AC', '0711000029', 9),
('CL030', 'Client AD', '0711000030', 10);

-- Equipment (25)
INSERT INTO equipment (name, type, daily_rate, is_available, category, status)
VALUES
('Excavator 200X', 'Heavy', 1500.00, TRUE, 'Construction', 'available'),
('Bulldozer XT', 'Heavy', 1800.00, TRUE, 'Construction', 'available'),
('Mini Loader M1', 'Medium', 950.00, TRUE, 'Earthmoving', 'available'),
('Drill Pro 10', 'Light', 300.00, TRUE, 'Tools', 'available'),
('CraneLift Z', 'Heavy', 2100.00, FALSE, 'Lifting', 'rented'),
('Concrete Mixer CM5', 'Medium', 850.00, TRUE, 'Concrete', 'available'),
('Forklift FL1', 'Medium', 1200.00, FALSE, 'Warehouse', 'rented'),
('Power Saw S5', 'Light', 250.00, TRUE, 'Tools', 'available'),
('Welder W2', 'Light', 400.00, TRUE, 'Tools', 'available'),
('Generator G3', 'Medium', 700.00, TRUE, 'Power', 'available'),
('Dump Truck DT1', 'Heavy', 1600.00, TRUE, 'Transport', 'available'),
('Scissor Lift SL2', 'Medium', 1100.00, TRUE, 'Lifting', 'available'),
('Jackhammer JH7', 'Light', 450.00, TRUE, 'Demolition', 'available'),
('Trencher T9', 'Medium', 1300.00, TRUE, 'Digging', 'available'),
('Compactor CP3', 'Medium', 1000.00, FALSE, 'Soil', 'rented'),
('Laser Level LL1', 'Light', 150.00, TRUE, 'Survey', 'available'),
('Hydraulic Breaker HB4', 'Medium', 1250.00, TRUE, 'Demolition', 'available'),
('Air Compressor AC2', 'Light', 500.00, TRUE, 'Air Tools', 'available'),
('Backhoe BH2', 'Heavy', 1550.00, TRUE, 'Earthmoving', 'available'),
('Concrete Saw CS3', 'Light', 350.00, TRUE, 'Concrete', 'available'),
('Roller R8', 'Medium', 1050.00, FALSE, 'Soil', 'rented'),
('Boom Lift B7', 'Heavy', 1750.00, TRUE, 'Lifting', 'available'),
('Paint Sprayer PS2', 'Light', 200.00, TRUE, 'Finishing', 'available'),
('Tile Cutter TC1', 'Light', 180.00, TRUE, 'Finishing', 'available'),
('Skid Steer SS9', 'Medium', 990.00, TRUE, 'Earthmoving', 'available');

-- Rentals (20)
INSERT INTO rentals (client_id, equipment_id, rental_date, return_date, otp_verified, payment_status, amount_due)
VALUES
(1, 1, '2025-07-01', '2025-07-03', TRUE, 'Paid', 4500.00),
(2, 5, '2025-07-05', NULL, FALSE, 'To Be Paid', 6300.00),
(3, 7, '2025-06-20', '2025-06-22', TRUE, 'Paid', 3600.00),
(4, 15, '2025-07-07', NULL, FALSE, 'To Be Paid', 2000.00),
(5, 21, '2025-06-30', '2025-07-01', TRUE, 'Paid', 1750.00),
(6, 11, '2025-07-01', '2025-07-03', TRUE, 'Paid', 4800.00),
(7, 13, '2025-07-02', NULL, FALSE, 'To Be Paid', 1350.00),
(8, 17, '2025-06-28', '2025-06-30', TRUE, 'Paid', 2500.00),
(9, 22, '2025-07-03', NULL, FALSE, 'To Be Paid', 200.00),
(10, 23, '2025-07-04', NULL, FALSE, 'To Be Paid', 180.00),
(11, 6, '2025-07-02', NULL, FALSE, 'To Be Paid', 2550.00),
(12, 9, '2025-07-03', NULL, FALSE, 'To Be Paid', 800.00),
(13, 3, '2025-07-01', '2025-07-02', TRUE, 'Paid', 950.00),
(14, 24, '2025-07-03', NULL, FALSE, 'To Be Paid', 990.00),
(15, 8, '2025-07-03', NULL, FALSE, 'To Be Paid', 250.00),
(16, 20, '2025-07-02', NULL, FALSE, 'To Be Paid', 350.00),
(17, 14, '2025-06-30', '2025-07-01', TRUE, 'Paid', 1300.00),
(18, 19, '2025-07-01', NULL, FALSE, 'To Be Paid', 1550.00),
(19, 4, '2025-07-01', NULL, FALSE, 'To Be Paid', 300.00),
(20, 12, '2025-07-04', NULL, FALSE, 'To Be Paid', 1100.00);

-- Admins (5)
INSERT INTO admins (email, password)
VALUES
('admin1@example.com', 'hashedpassword1'),
('admin2@example.com', 'hashedpassword2'),
('admin3@example.com', 'hashedpassword3'),
('admin4@example.com', 'hashedpassword4'),
('admin5@example.com', 'hashedpassword5');
