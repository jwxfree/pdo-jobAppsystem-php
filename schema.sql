CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL
);

CREATE TABLE doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    address TEXT,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

CREATE TABLE job_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    position VARCHAR(100),
    status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    cover_letter TEXT,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

CREATE TABLE qualifications (
    qualification_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    degree VARCHAR(100),
    institution VARCHAR(100),
    graduation_year YEAR,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

CREATE TABLE experience (
    experience_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    job_title VARCHAR(100),
    organization VARCHAR(100),
    start_date DATE,
    end_date DATE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

CREATE TABLE certifications (
    certification_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    certification_name VARCHAR(100),
    institution VARCHAR(100),
    certification_date DATE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);


INSERT INTO departments (department_name)
VALUES 
('Cardiology'),
('Neurology'),
('Pediatrics'),
('Oncology'),
('Orthopedics'),
('Radiology'),
('Dermatology'),
('Psychiatry'),
('Endocrinology'),
('Urology');


INSERT INTO doctors (first_name, last_name, email, phone_number, date_of_birth, gender, address, department_id)
VALUES
('John', 'Doe', 'johndoe@example.com', '1234567890', '1985-05-10', 'Male', '123 Elm Street, Springfield', 1),
('Jane', 'Smith', 'janesmith@example.com', '0987654321', '1990-07-15', 'Female', '456 Oak Avenue, Metropolis', 2),
('Emily', 'Johnson', 'emilyj@example.com', '5551234567', '1988-09-20', 'Female', '789 Pine Road, Gotham', 3),
('Michael', 'Brown', 'michaelb@example.com', '4449876543', '1980-03-22', 'Male', '321 Cedar Lane, Star City', 4),
('William', 'Clark', 'williamc@example.com', '3332221110', '1982-11-12', 'Male', '22 Birch Street, Liberty City', 5),
('Sophia', 'Davis', 'sophiad@example.com', '8887776665', '1991-04-18', 'Female', '12 Maple Drive, Smallville', 6),
('Daniel', 'Garcia', 'danielg@example.com', '6665554443', '1989-02-08', 'Male', '99 Walnut Road, Central City', 7),
('Olivia', 'Martinez', 'oliviam@example.com', '7778889990', '1987-12-01', 'Female', '44 Cypress Lane, Hill Valley', 8);


INSERT INTO job_applications (doctor_id, position, status, cover_letter)
VALUES
(1, 'Cardiologist', 'Pending', 'I am highly motivated to work in the cardiology department.'),
(2, 'Neurologist', 'Accepted', 'My expertise aligns with the needs of the neurology team.'),
(3, 'Pediatrician', 'Rejected', 'I have extensive experience in pediatric care.'),
(4, 'Oncologist', 'Pending', 'I am passionate about advancing oncology research.'),
(5, 'Radiologist', 'Pending', 'Excited to bring my radiology skills to your team.'),
(6, 'Dermatologist', 'Accepted', 'Dermatology is my passion, and I have extensive clinical experience.'),
(7, 'Psychiatrist', 'Rejected', 'Committed to providing mental health care with compassion and expertise.'),
(8, 'Endocrinologist', 'Pending', 'Experienced in treating a wide range of endocrine disorders.');

INSERT INTO qualifications (doctor_id, degree, institution, graduation_year)
VALUES
(1, 'MD Cardiology', 'Harvard Medical School', 2010),
(2, 'MD Neurology', 'Stanford University', 2015),
(3, 'MD Pediatrics', 'Johns Hopkins University', 2012),
(4, 'MD Oncology', 'University of Chicago', 2008),
(5, 'MD Radiology', 'University of Michigan', 2013),
(6, 'MD Dermatology', 'University of California, San Francisco', 2016),
(7, 'MD Psychiatry', 'Yale University', 2014),
(8, 'MD Endocrinology', 'Duke University', 2011);


INSERT INTO experience (doctor_id, job_title, organization, start_date, end_date)
VALUES
(1, 'Resident Cardiologist', 'General Hospital', '2011-01-01', '2015-12-31'),
(2, 'Junior Neurologist', 'Metro Medical Center', '2016-01-01', '2020-12-31'),
(3, 'Pediatrician', 'Children’s Hospital', '2013-01-01', '2020-12-31'),
(4, 'Oncology Researcher', 'Cancer Research Institute', '2009-01-01', '2015-12-31'),
(5, 'Radiology Intern', 'City General Hospital', '2014-01-01', '2018-12-31'),
(6, 'Dermatology Resident', 'Skin Wellness Center', '2017-01-01', '2021-12-31'),
(7, 'Junior Psychiatrist', 'Mental Health Institute', '2015-01-01', '2020-12-31'),
(8, 'Endocrinologist', 'Diabetes Care Clinic', '2012-01-01', '2019-12-31');


INSERT INTO certifications (doctor_id, certification_name, institution, certification_date)
VALUES
(1, 'Board Certified in Cardiology', 'American Board of Internal Medicine', '2012-06-15'),
(2, 'Certified Neurologist', 'American Neurological Association', '2016-07-20'),
(3, 'Certified Pediatrician', 'American Academy of Pediatrics', '2013-08-25'),
(4, 'Certified Oncologist', 'American Society of Clinical Oncology', '2010-09-10'),
(5, 'Board Certified Radiologist', 'Radiological Society of North America', '2014-05-10'),
(6, 'Certified Dermatologist', 'American Academy of Dermatology', '2018-03-15'),
(7, 'Licensed Psychiatrist', 'American Psychiatric Association', '2016-11-20'),
(8, 'Board Certified Endocrinologist', 'Endocrine Society', '2012-07-18');
