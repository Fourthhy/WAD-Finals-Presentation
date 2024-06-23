CREATE DATABASE iVote;

CREATE TABLE poll_session (
    poll_id INT PRIMARY KEY AUTO_INCREMENT,
    poll_title VARCHAR(20),
    poll_key VARCHAR(10)
);

CREATE TABLE poll_session_options (
    poll_session_options_id INT PRIMARY KEY AUTO_INCREMENT,
    poll_id INT,
    poll_option VARCHAR(50),
    FOREIGN KEY (poll_id) REFERENCES poll_session(poll_id)
);

CREATE TABLE registered_users (
    users_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    voted_confirmation BOOLEAN DEFAULT FALSE
);

CREATE TABLE analytic_records (
    record_id INT PRIMARY KEY AUTO_INCREMENT,
    poll_id INT,
    poll_session_options_id INT,
    users_id INT,
    FOREIGN KEY (poll_id) REFERENCES poll_session(poll_id),
    FOREIGN KEY (poll_session_options_id) REFERENCES poll_session_options(poll_session_options_id),
    FOREIGN KEY (users_id) REFERENCES registered_users(users_id)
);

INSERT INTO registered_users (username) VALUES
("Rizalyne"), ("Aerrol"), ("Aikee"), ("Allen"), ("Alicia"), ("Anthony"), ("Astrid"),
("Christian"), ("Daniel"), ("Ellaine"), ("Elle"), ("Ellen"), ("Jasmine"), ("Jean"),
("Jhemyll"), ("Justine"), ("Kien"), ("Krystel"), ("Kylie"), ("Louie"), ("Lenie"), 
("Lian"), ("Lorenz"), ("Joseph"), ("Miguel"), ("Princess"), ("Mia"), ("Rafael"),
("Rainier"), ("Rodolfo"), ("Rome"), ("Roylyn"), ("Shaina"), ("Sherelyn"), ("Shiela"),
("Sydney"), ("Olorvida"), ("Catibog");

