DROP DATABASE IF EXISTS vacation_tracker;

CREATE DATABASE vacation_tracker;

USE vacation_tracker;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code INT NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('manager', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vacation_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason VARCHAR(255),
    status ENUM(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

INSERT INTO
    users (
        name,
        email,
        password,
        role,
        employee_code
    )
VALUES (
        'Admin',
        'admin@epignosis.com',
        '$2y$10$EGRWA4Yx8Y1ivB2zAL0hkumh91ScXYQfBvMVt88PnP2uk3I5YFDse',
        'manager',
        1000000
    ),
    (
        'Bob Employee',
        'bob.employee@example.com',
        '$2y$10$EGRWA4Yx8Y1ivB2zAL0hkumh91ScXYQfBvMVt88PnP2uk3I5YFDse',
        'employee',
        2345678
    ),
    (
        'Chris Tacker',
        'chris@epignosis.com',
        '$2y$10$EGRWA4Yx8Y1ivB2zAL0hkumh91ScXYQfBvMVt88PnP2uk3I5YFDse',
        'employee',
        2345679
    ),
    (
        'George Mitsos',
        'giorg.mitsos@gmail.com',
        '$2y$10$EGRWA4Yx8Y1ivB2zAL0hkumh91ScXYQfBvMVt88PnP2uk3I5YFDse',
        'employee',
        2342678
    );

INSERT INTO
    vacation_requests (
        user_id,
        start_date,
        end_date,
        status,
        reason
    )
VALUES (
        2,
        '2024-12-01',
        '2024-12-03',
        'pending',
        'sick'
    ),
    (
        3,
        '2025-12-04',
        '2025-12-05',
        'approved',
        'parental leave'
    ),
    (
        4,
        '2024-12-06',
        '2024-12-07',
        'pending',
        'sick'
    ),
    (
        2,
        '2025-12-08',
        '2025-12-09',
        'rejected',
        'parental leave'
    ),
    (
        2,
        '2024-12-10',
        '2024-12-11',
        'pending',
        'sick'
    ),
    (
        3,
        '2025-12-12',
        '2025-12-13',
        'pending',
        'parental leave'
    ),
    (
        3,
        '2024-12-14',
        '2024-12-15',
        'pending',
        'sick'
    ),
    (
        4,
        '2025-12-16',
        '2025-12-17',
        'approved',
        'parental leave'
    )