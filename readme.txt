1. Make sure your reports table has these columns:

sql
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    description TEXT,
    location VARCHAR(255),
    crime_type VARCHAR(100),
    event_time DATETIME,
    evidence_file VARCHAR(255),
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

2. Make sure your users table has these columns:

sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    role VARCHAR(50),
    password VARCHAR(255)
);

3. Update Your users Table to Add role
Run this SQL command in your database to add a role column:

sql
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';

Then, manually make your admin account by updating the role like this:

sql
UPDATE users SET role = 'admin' WHERE email = 'your_admin_email@example.com';
Replace with your actual email.