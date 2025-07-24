CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$K4EFGEnkHHv68vhCGY4CdelxjrL9zjQuuDi8q/YZOPIJuMjX4BGA.');
