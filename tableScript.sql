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

-- Machen Sie Selber INSERTS