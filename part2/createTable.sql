mysql> use wortiz
Database changed
mysql> source congress_db_create_tables.sql;
Empty set (0.00 sec)

Query OK, 0 rows affected (0.08 sec)

Query OK, 0 rows affected (0.08 sec)

Query OK, 0 rows affected (0.08 sec)

Query OK, 0 rows affected (0.08 sec)

Query OK, 0 rows affected (0.07 sec)

Query OK, 0 rows affected (0.13 sec)

Query OK, 0 rows affected (0.10 sec)

Query OK, 0 rows affected (0.11 sec)

Query OK, 0 rows affected (0.07 sec)

Query OK, 0 rows affected (0.07 sec)

Query OK, 0 rows affected (0.08 sec)

Query OK, 0 rows affected (0.21 sec)

+------------------+
| Tables_in_wortiz |
+------------------+
| Amendment        |
| Bill             |
| Chamber          |
| Congress         |
| District         |
| Legislator       |
| Legislator_Vote  |
| Session          |
| State            |
| Subjects         |
| Term             |
| Vote             |
+------------------+
12 rows in set (0.00 sec)

+---------------+-------------+------+-----+---------+-------+
| Field         | Type        | Null | Key | Default | Extra |
+---------------+-------------+------+-----+---------+-------+
| id            | varchar(45) | NO   | PRI | NULL    |       |
| description   | text        | YES  |     | NULL    |       |
| purpose       | text        | YES  |     | NULL    |       |
| status        | varchar(45) | YES  |     | NULL    |       |
| introduced_at | date        | YES  |     | NULL    |       |
| status_at     | date        | YES  |     | NULL    |       |
| type          | varchar(45) | NO   |     | NULL    |       |
| Bill_id       | varchar(45) | YES  | MUL | NULL    |       |
| Amendment_id  | varchar(45) | YES  |     | NULL    |       |
| congress      | int(11)     | NO   | MUL | NULL    |       |
| number        | int(11)     | NO   |     | NULL    |       |
+---------------+-------------+------+-----+---------+-------+
11 rows in set (0.00 sec)

+-------------------+--------------+------+-----+---------+-------+
| Field             | Type         | Null | Key | Default | Extra |
+-------------------+--------------+------+-----+---------+-------+
| id                | varchar(45)  | NO   | PRI | NULL    |       |
| type              | varchar(45)  | NO   |     | NULL    |       |
| title             | varchar(256) | NO   |     | NULL    |       |
| popular_title     | varchar(256) | YES  |     | NULL    |       |
| short_title       | varchar(256) | YES  |     | NULL    |       |
| status            | varchar(45)  | YES  |     | NULL    |       |
| introduction_date | date         | YES  |     | NULL    |       |
| summary           | text         | YES  |     | NULL    |       |
| congress          | int(11)      | NO   | MUL | NULL    |       |
| number            | int(11)      | NO   |     | NULL    |       |
+-------------------+--------------+------+-----+---------+-------+
10 rows in set (0.00 sec)

+-----------+-------------+------+-----+---------+-------+
| Field     | Type        | Null | Key | Default | Extra |
+-----------+-------------+------+-----+---------+-------+
| id        | varchar(1)  | NO   | PRI | NULL    |       |
| name      | varchar(60) | NO   |     | NULL    |       |
| num_seats | int(11)     | NO   |     | NULL    |       |
+-----------+-------------+------+-----+---------+-------+
3 rows in set (0.00 sec)

+-------+---------+------+-----+---------+-------+
| Field | Type    | Null | Key | Default | Extra |
+-------+---------+------+-----+---------+-------+
| id    | int(11) | NO   | PRI | NULL    |       |
| begin | date    | NO   |     | NULL    |       |
| end   | date    | NO   |     | NULL    |       |
+-------+---------+------+-----+---------+-------+
3 rows in set (0.00 sec)

+--------+-------------+------+-----+---------+-------+
| Field  | Type        | Null | Key | Default | Extra |
+--------+-------------+------+-----+---------+-------+
| number | int(11)     | NO   | PRI | NULL    |       |
| state  | varchar(60) | NO   | PRI | NULL    |       |
+--------+-------------+------+-----+---------+-------+
2 rows in set (0.00 sec)

+--------------------+-------------+------+-----+---------+-------+
| Field              | Type        | Null | Key | Default | Extra |
+--------------------+-------------+------+-----+---------+-------+
| bioguide_id        | varchar(45) | NO   | PRI | NULL    |       |
| Last Name          | varchar(45) | NO   |     | NULL    |       |
| First Name         | varchar(45) | NO   |     | NULL    |       |
| birthday           | date        | YES  |     | NULL    |       |
| gender             | varchar(1)  | YES  |     | NULL    |       |
| wikipedia_id       | varchar(90) | YES  |     | NULL    |       |
| govtrack_id        | int(11)     | NO   |     | NULL    |       |
| official_full_name | varchar(90) | YES  |     | NULL    |       |
+--------------------+-------------+------+-----+---------+-------+
8 rows in set (0.00 sec)

+-------------+-------------+------+-----+---------+-------+
| Field       | Type        | Null | Key | Default | Extra |
+-------------+-------------+------+-----+---------+-------+
| Vote_id     | varchar(45) | NO   | PRI | NULL    |       |
| bioguide_id | varchar(45) | NO   | PRI | NULL    |       |
| how_voted   | varchar(45) | NO   |     | NULL    |       |
+-------------+-------------+------+-----+---------+-------+
3 rows in set (0.00 sec)

+----------+-------------+------+-----+---------+-------+
| Field    | Type        | Null | Key | Default | Extra |
+----------+-------------+------+-----+---------+-------+
| congress | int(11)     | NO   | PRI | NULL    |       |
| type     | varchar(45) | NO   | PRI | NULL    |       |
| begin    | date        | NO   |     | NULL    |       |
| end      | date        | NO   |     | NULL    |       |
+----------+-------------+------+-----+---------+-------+
4 rows in set (0.00 sec)

+---------------------+-------------+------+-----+---------+-------+
| Field               | Type        | Null | Key | Default | Extra |
+---------------------+-------------+------+-----+---------+-------+
| name                | varchar(60) | NO   | PRI | NULL    |       |
| num_districts       | int(11)     | NO   |     | NULL    |       |
| num_representatives | int(11)     | NO   |     | NULL    |       |
+---------------------+-------------+------+-----+---------+-------+
3 rows in set (0.00 sec)

+---------+--------------+------+-----+---------+-------+
| Field   | Type         | Null | Key | Default | Extra |
+---------+--------------+------+-----+---------+-------+
| subject | varchar(128) | NO   | PRI | NULL    |       |
| Bill_id | varchar(45)  | NO   | PRI | NULL    |       |
+---------+--------------+------+-----+---------+-------+
2 rows in set (0.01 sec)

+-------------+---------------+------+-----+---------+-------+
| Field       | Type          | Null | Key | Default | Extra |
+-------------+---------------+------+-----+---------+-------+
| bioguide_id | varchar(45)   | NO   | PRI | NULL    |       |
| start       | date          | NO   | PRI | NULL    |       |
| end         | date          | NO   | PRI | NULL    |       |
| type        | varchar(3)    | NO   |     | NULL    |       |
| state       | varchar(60)   | NO   | MUL | NULL    |       |
| url         | varchar(1024) | YES  |     | NULL    |       |
| district    | int(11)       | YES  | MUL | NULL    |       |
| party       | varchar(45)   | NO   |     | NULL    |       |
| chamber     | varchar(1)    | NO   | MUL | NULL    |       |
+-------------+---------------+------+-----+---------+-------+
9 rows in set (0.00 sec)

+--------------+--------------+------+-----+---------+-------+
| Field        | Type         | Null | Key | Default | Extra |
+--------------+--------------+------+-----+---------+-------+
| id           | varchar(45)  | NO   | PRI | NULL    |       |
| chamber      | varchar(1)   | NO   | MUL | NULL    |       |
| category     | varchar(45)  | NO   |     | NULL    |       |
| question     | varchar(256) | YES  |     | NULL    |       |
| congress     | int(11)      | NO   | MUL | NULL    |       |
| session      | year(4)      | NO   |     | NULL    |       |
| result       | varchar(45)  | YES  |     | NULL    |       |
| requires     | varchar(45)  | YES  |     | NULL    |       |
| number       | int(11)      | NO   |     | NULL    |       |
| date         | date         | NO   |     | NULL    |       |
| type         | varchar(45)  | NO   |     | NULL    |       |
| Bill_id      | varchar(45)  | YES  | MUL | NULL    |       |
| Amendment_id | varchar(45)  | YES  |     | NULL    |       |
+--------------+--------------+------+-----+---------+-------+
13 rows in set (0.00 sec)

mysql> exit
