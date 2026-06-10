USE LibSpace;
INSERT INTO users (username, password, role) VALUES ('testuser1', '$2y$10$test', 'user');
SELECT * FROM users ORDER BY id DESC LIMIT 1;
