CREATE TABLE Sotrudniki (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL,
    age INTEGER NOT NULL,
    region TEXT NOT NULL
);

CREATE TABLE Zatrats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL
    
);

CREATE TABLE Departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL,
    city TEXT NOT NULL
);

CREATE TABLE Kans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    SotrudnikID INTEGER NOT NULL,
    ZatratId INTEGER NOT NULL,
    DepartmentID INTEGER NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (SotrudnikID) REFERENCES Sotrudniki (id),
    FOREIGN KEY (ZatratId) REFERENCES Zatrats (id),
    FOREIGN KEY (DepartmentID) REFERENCES Departments (id)
);