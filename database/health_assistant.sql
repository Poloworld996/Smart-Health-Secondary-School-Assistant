

CREATE DATABASE IF NOT EXISTS health_assistant;
USE health_assistant;


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','teacher','admin') NOT NULL DEFAULT 'student',
    class_form VARCHAR(30) DEFAULT NULL,   -- e.g. "Form 3A" (used for students)
    status ENUM('active','inactive') NOT NULL DEFAULT 'active', -- 'inactive' = deactivated by admin (kept, not deleted, so their health records are preserved)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE bmi_records (
    bmi_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    height_cm DECIMAL(5,2) NOT NULL,
    weight_kg DECIMAL(5,2) NOT NULL,
    bmi_value DECIMAL(5,2) NOT NULL,
    bmi_category VARCHAR(30) NOT NULL,
    date_recorded TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT
);


CREATE TABLE symptom_checks (
    check_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    symptoms_selected TEXT NOT NULL,
    possible_condition VARCHAR(150) NOT NULL,
    advice_given TEXT NOT NULL,
    date_checked TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT
);


CREATE TABLE health_reminders (
    reminder_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    reminder_date DATE NOT NULL,
    status ENUM('pending','done') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT
);


CREATE TABLE emergency_contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_role VARCHAR(50) NOT NULL,  -- e.g. School Nurse, Ambulance, Counselor
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(100) DEFAULT NULL
);


CREATE TABLE health_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'General',
    posted_by INT DEFAULT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(user_id) ON DELETE SET NULL
);


CREATE TABLE announcements (
    announcement_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    posted_by INT DEFAULT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(user_id) ON DELETE SET NULL
);


INSERT INTO users (full_name, username, email, password, role, class_form, status) VALUES
('Admin User', 'admin', 'admin@school.com', '$2y$10$d7VcnGuVxOpG242CCvAebO4XK1UJ0IT7La6tCCWozMW29JPMoZKLe', 'admin', NULL, 'active'),
('Mr. John Teacher', 'teacher1', 'teacher@school.com', '$2y$10$d7VcnGuVxOpG242CCvAebO4XK1UJ0IT7La6tCCWozMW29JPMoZKLe', 'teacher', NULL, 'active'),
('Amina Student', 'student1', 'amina@school.com', '$2y$10$d7VcnGuVxOpG242CCvAebO4XK1UJ0IT7La6tCCWozMW29JPMoZKLe', 'student', 'Form 3A', 'active');

INSERT INTO emergency_contacts (name, contact_role, phone, email) VALUES
('School Nurse - Mrs. Grace', 'School Nurse', '0712345678', 'nurse@school.com'),
('School Counselor - Mr. Peter', 'Counselor', '0713456789', 'counselor@school.com'),
('Local Hospital Ambulance', 'Ambulance', '0800110110', NULL),
('Police Emergency', 'Police', '112', NULL);

INSERT INTO health_materials (title, content, category, posted_by) VALUES
('Importance of Handwashing', 'Washing your hands with soap for at least 20 seconds helps prevent the spread of diseases such as diarrhea and flu. Always wash before eating and after using the toilet.', 'Hygiene', 1),
('Balanced Diet for Teenagers', 'A balanced diet should include carbohydrates, proteins, vitamins, minerals, and enough water. Avoid too much sugar and junk food to stay healthy and focused in class.', 'Nutrition', 1),
('Mental Health Awareness', 'It is normal to feel stressed sometimes. Talk to a trusted teacher, counselor, or parent if you feel overwhelmed. You are not alone.', 'Mental Health', 1);

INSERT INTO announcements (title, message, posted_by) VALUES
('Welcome to the Health Assistant System', 'All students are encouraged to use the BMI calculator and symptom checker regularly to monitor their health.', 2);
