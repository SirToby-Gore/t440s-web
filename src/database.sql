CREATE TABLE users (
        ID int auto_increment primary key,
        username text not null,
        email text not null unique,
        password text not null,
        created_at timestamp default current_timestamp,
        img_url text
);


CREATE TABLE bookings (
        ID int auto_increment primary key,
        user_id int not null,
        solar boolean not null,
        ev_charger boolean not null,
        smart_home boolean not null,
        date text not null,
        address TEXT NOT NULL,
        foreign key (user_id) references users(ID)
);

CREATE TABLE carbon_footprints (
        ID int auto_increment primary key,
        user_id int not null,
        tonnes float not null,
        uploaded_at timestamp default current_timestamp,
        foreign key (user_id) references users(ID)
);
