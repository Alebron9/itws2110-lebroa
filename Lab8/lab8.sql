CREATE DATABASE websyslab8;
USE websyslab8;

-- Courses table
CREATE TABLE courses (
  crn INT(11) PRIMARY KEY,
  prefix VARCHAR(4) NOT NULL,
  number SMALLINT(4) NOT NULL,
  title VARCHAR(255) NOT NULL
) COLLATE utf8_unicode_ci;

-- Students table
CREATE TABLE students (
  rin INT(9) PRIMARY KEY,
  rcsID CHAR(7),
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  alias VARCHAR(100) NOT NULL,
  phone INT(10)
) COLLATE utf8_unicode_ci;

-- Address fields to students table
ALTER TABLE students ADD COLUMN street VARCHAR(255);
ALTER TABLE students ADD COLUMN city VARCHAR(100);
ALTER TABLE students ADD COLUMN state VARCHAR(2);
ALTER TABLE students ADD COLUMN zip VARCHAR(10);

-- Section and year fields to courses table
ALTER TABLE courses ADD COLUMN section VARCHAR(10);
ALTER TABLE courses ADD COLUMN year INT(4);

-- Grades table with foreign keys
CREATE TABLE grades (
  id INT PRIMARY KEY AUTO_INCREMENT,
  crn INT(11),
  rin INT(9),
  grade INT(3) NOT NULL,
  FOREIGN KEY (crn) REFERENCES courses(crn),
  FOREIGN KEY (rin) REFERENCES students(rin)
) COLLATE utf8_unicode_ci;

-- 4 Courses
INSERT INTO courses (crn, prefix, number, title, section, year) VALUES
(12345, 'CSCI', 1100, 'Computer Science I', '01', 2025),
(12346, 'CSCI', 2500, 'Computer Architecture', '02', 2025),
(12347, 'MATH', 1010, 'Calculus I', '01', 2025),
(12348, 'PHYS', 1100, 'Physics I', '03', 2025);

-- 4 Students
INSERT INTO students (rin, rcsID, first_name, last_name, alias, phone, street, city, state, zip) VALUES
(123456789, 'abc123', 'John', 'Smith', 'JS', 5185551234, '123 Main St', 'Troy', 'NY', '12180'),
(123456790, 'def456', 'Alice', 'Johnson', 'AJ', 5185551235, '456 Oak Ave', 'Albany', 'NY', '12210'),
(123456791, 'ghi789', 'Bob', 'Williams', 'BW', 5185551236, '789 Pine Rd', 'Schenectady', 'NY', '12305'),
(123456792, 'jkl012', 'Carol', 'Brown', 'CB', 5185551237, '321 Elm St', 'Troy', 'NY', '12181');

-- 10 Grades
INSERT INTO grades (crn, rin, grade) VALUES
(12345, 123456789, 92),
(12345, 123456790, 88),
(12346, 123456791, 95),
(12346, 123456792, 85),
(12347, 123456789, 91),
(12347, 123456790, 87),
(12348, 123456791, 93),
(12348, 123456792, 89),
(12345, 123456791, 96),
(12346, 123456789, 84);

-- Alphabetical order by RIN, last name, RCSid, and firstname
SELECT * FROM students ORDER BY rin, last_name, rcsID, first_name;

-- List students' grade in any course higher than 90
SELECT DISTINCT s.rin, s.first_name, s.last_name, s.street, s.city, s.state, s.zip
FROM students s
JOIN grades g ON s.rin = g.rin
WHERE g.grade > 90;

-- List out the average grade in each course
SELECT c.crn, c.prefix, c.number, c.title, AVG(g.grade) as average_grade
FROM courses c
LEFT JOIN grades g ON c.crn = g.crn
GROUP BY c.crn, c.prefix, c.number, c.title;

-- List out the number of students in each course
SELECT c.crn, c.prefix, c.number, c.title, COUNT(DISTINCT g.rin) as student_count
FROM courses c
LEFT JOIN grades g ON c.crn = g.crn
GROUP BY c.crn, c.prefix, c.number, c.title;