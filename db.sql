CREATE DATABASE IF NOT EXISTS notice_board CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE notice_board;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notices (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  content TEXT NOT NULL,
  priority ENUM('normal','important','urgent') NOT NULL DEFAULT 'normal',
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  expiry_date DATE NULL,
  author_id INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (status), INDEX (expiry_date),
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  notice_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  comment TEXT NOT NULL,
  status ENUM('pending','approved') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO notices (title, content, priority, status, expiry_date) VALUES
('Exam Schedule', 'The semester examination schedule has been released. Please check your department notice board for timings and halls.', 'important', 'published', '2026-12-31'),
('Placement Drive', 'Campus placement drive starts next week. All final-year students must register before the deadline.', 'urgent', 'published', '2026-11-30'),
('Sports Day', 'Annual Sports Day will be held on the main ground. Participation forms are available at the sports office.', 'normal', 'published', '2026-12-15');
