PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name     TEXT NOT NULL,
    email         TEXT NOT NULL UNIQUE,
    phone         TEXT,
    password_hash TEXT NOT NULL,
    created_at    TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS trips (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    company      TEXT NOT NULL,
    origin       TEXT NOT NULL,
    destination  TEXT NOT NULL,
    departure_at TEXT NOT NULL,
    arrival_at   TEXT NOT NULL,
    price        INTEGER NOT NULL CHECK (price > 0),
    seats_total  INTEGER NOT NULL CHECK (seats_total > 0),
    bus_type     TEXT NOT NULL DEFAULT 'Standard'
);
CREATE INDEX IF NOT EXISTS idx_trips_search ON trips (origin, destination, departure_at);

CREATE TABLE IF NOT EXISTS bookings (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    reference       TEXT NOT NULL UNIQUE,
    user_id         INTEGER NOT NULL REFERENCES users (id),
    trip_id         INTEGER NOT NULL REFERENCES trips (id),
    passengers_count INTEGER NOT NULL CHECK (passengers_count BETWEEN 1 AND 5),
    total           INTEGER NOT NULL,
    payment_method  TEXT NOT NULL,
    status          TEXT NOT NULL DEFAULT 'paid' CHECK (status IN ('paid', 'cancelled')),
    created_at      TEXT NOT NULL DEFAULT (datetime('now'))
);
CREATE INDEX IF NOT EXISTS idx_bookings_user ON bookings (user_id);
CREATE INDEX IF NOT EXISTS idx_bookings_trip ON bookings (trip_id);

CREATE TABLE IF NOT EXISTS passengers (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    booking_id  INTEGER NOT NULL REFERENCES bookings (id) ON DELETE CASCADE,
    full_name   TEXT NOT NULL,
    seat_number INTEGER NOT NULL
);
