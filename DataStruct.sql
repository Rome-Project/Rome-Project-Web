create database if not exists rome_project_db;
use rome_project_db;

-- Web User
create table User (
    User_ID bigint primary key unique auto_increment,
    Username varchar(50) not null unique,
    Password varchar(255) not null,
    Email varchar(100) default null,
    Role enum('Developer', 'Influencer', 'Player') default 'Player',
    Created_At timestamp default current_timestamp,
    Last_Login timestamp default current_timestamp on update current_timestamp,
    IsEnabled boolean default 1,
    
    index idx_id (User_ID),
    index idx_username (Username)
);

create table User_Settings (
    User_ID bigint primary key,
    Receive_Daily_Stats boolean default 0,
    Profile_Image varchar(255) default null, -- Not the actual image btw, just a link to the image
    foreign key (User_ID) references User(User_ID) on delete cascade
);

-- Registration Token
create table Registration_Token (
	Token varchar(64) primary key unique,
    Moderator varchar(50) not null,
    Role enum('Developer', 'Influencer', 'Player') default 'Player',
    Created_At timestamp default current_timestamp,
    IsUsed boolean default 0
);

-- Action History logs
create table Action_History (
    Action_ID int auto_increment primary key,
    Moderator varchar(50) not null,
    Action_Info varchar(255) default 'Info not provided',
    Severity enum('low', 'medium', 'high') default 'low',
    Created_At timestamp default current_timestamp
);

-- Game Ban Data
create table GameBans (
    Player_ID bigint unique not null,
    Moderator bigint not null,
    Reason varchar(255) default 'No reason provided',
    Ban_Start timestamp default current_timestamp,
    Duration bigint default 3600
)