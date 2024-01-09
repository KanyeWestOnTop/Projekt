DROP DATABASE IF EXISTS MyTraining;
CREATE DATABASE MyTraining;
USE MyTraining;
CREATE TABLE user 
(
	id int not null auto_increment Primary Key,
	email VARCHAR(255),
    password VARCHAR(255),
    prename VARCHAR(255),
    lastname VARCHAR(255)
);
CREATE TABLE exercise 
(
	id int not null auto_increment Primary Key,
    user_id int not null,
	name VARCHaR(255)
);
CREATE TABLE progress
(
    id int not null auto_increment Primary Key,
	user_id int not null,
    exercise_id int not null,
    weight int,
    reps int,
    date date,
	FOREIGN KEY (user_id) references user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    foreign key (exercise_id) references exercise(id) ON DELETE CASCADE ON UPDATE CASCADE
);


Insert into user(email, password, prename, lastname) Values ("marrerkevin@gmail.com",
 "hallo123", "Kevin", "Marrer");
 Insert into exercise(user_id ,name) Values(1, "Benchpress");
INSERT INTO progress(user_id, exercise_id, weight, reps, date) VALUES (1, 1, 100, 10, "2020-01-01");
 