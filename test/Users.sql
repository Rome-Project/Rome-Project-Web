USE rome_project_db;
INSERT INTO users (username, password_hash, last_ip)
VALUES('testuser', '$2y$10$9zX8zR8zZ8zZ8zZ8zR8zZ8zZ8zZ8zR8zZ8zZ8zZ8zR8zZ8zZ8zZ8zR', INET6_ATON('192.168.1.100'));