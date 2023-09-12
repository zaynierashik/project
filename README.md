# Hamrocollege: College Information System



# Import the 'database.sql' file in the XAMPP Server to create the database and tables.

# If you want to manually create the database, follow the reference below:
# Building a database
  Database name: database

  Database table: admin_data, admission_data, chatbot, college_data, course_data, feedback_data, institution_data and user_data
    1. admin_data table should have column: adminId [primary key], name, phone, email, password[255] and role[defined: admin]
    2. admission_data table should have column: admissionId [primary key], username, phone, email, collegeId, title and message
    3. chatbot table should have column: id [primary key], queries, replies
    4. college_course table should have column: collegeId and courseId [use reference from the line 28]
    5. college_data table should have column: collegeId [primary key], name, overview, message, reason, program, phone, email, website, address, location, logo and affiliation
    6. course_data table should have column: courseId [primary key], affiliation, field, title, abbreviation, content, eligibility, job and career
    7. feedback_data table should have column: feedbackId [primary key], username, email and feedback
    8. institution_data table should have column: institutionId [primary key], name, phone, email, password[255] and role[defined: institution]
    9. user_data table should have column: userId [primary key], name, phone, email, password[255] and role[defined: user]

  Database view: authentication
    1. authentication view should have column: name, email, password[255] and role

# For authentication view: 
  CREATE OR REPLACE VIEW authentication AS SELECT name, email, password, role FROM institution_data UNION SELECT name, email, password, role FROM user_data;

# For college_course table:
  CREATE TABLE college_course(
    collegeId INT,
    courseId INT,
    PRIMARY KEY (collegeId, courseId),
    FOREIGN KEY (collegeId) REFERENCES college_data(collegeId),
    FOREIGN KEY (courseId) REFERENCES course_data(courseId)
  );