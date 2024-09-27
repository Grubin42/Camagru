-- Suppression des tables existantes si elles sont présentes
DROP TABLE IF EXISTS commentaire CASCADE;
DROP TABLE IF EXISTS post CASCADE;
DROP TABLE IF EXISTS likes CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- Recréer les tables
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    notif BOOLEAN DEFAULT TRUE,
    reset_token VARCHAR(100),
    reset_token_expiration TIMESTAMPTZ,
    is_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS post (
    id SERIAL PRIMARY KEY,
    image BYTEA NOT NULL,
    created_date TIMESTAMPTZ DEFAULT NOW(),
    user_id INT REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS commentaire (
    id SERIAL PRIMARY KEY,
    commentaire TEXT NOT NULL,
    username VARCHAR(50) NOT NULL,
    created_date TIMESTAMPTZ DEFAULT NOW(),
    post_id INT REFERENCES post(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS likes (
    id SERIAL PRIMARY KEY,
    post_id INT REFERENCES post(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE
);