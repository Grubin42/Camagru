-- Vider la table users avant de la remplir
TRUNCATE TABLE users RESTART IDENTITY CASCADE;

-- Insertion des utilisateurs
INSERT INTO users (username, email, password, notif) VALUES
('user1', 'user1@example.com', 'password1', TRUE),
('user2', 'user2@example.com', 'password2', TRUE),
('user3', 'user3@example.com', 'password3', TRUE),
('user4', 'user4@example.com', 'password4', FALSE),
('user5', 'user5@example.com', 'password5', TRUE);