CREATE DATABASE tablestudent;
USE tablestudent;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    group_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL
);
CREATE TABLE IF NOT EXISTS professors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL
);
CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    group_id INT NOT NULL,
    date DATE NOT NULL,
    opened_by INT NOT NULL,
    status ENUM('open','closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (group_id) REFERENCES groups(id),
    FOREIGN KEY (opened_by) REFERENCES professors(id)
);
CREATE TABLE IF NOT EXISTS attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('present','absent') NOT NULL,

    FOREIGN KEY (session_id) REFERENCES attendance_sessions(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);
