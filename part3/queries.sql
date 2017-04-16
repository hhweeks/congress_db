/*
1
How many amendments were introduced before 2008 in each branch?
*/
select type, count(type) from Amendment where introduced_at < '2008' group by type;

/*
2
Which legislators were born before the outset of WWI?
*/
Select distinct bioguide_id, `First Name` , `Last Name`, party  from Legislator natural join Term where birthday < '1914-07-28' limit 10;

/*
3
Find all Legislators that shared a birthday
*/
select L1.birthday, L1.bioguide_id, L1.`First Name`, L1.`Last Name`, L2.bioguide_id, L2.`First Name`, L2.`Last Name` from Legislator L1, Legislator L2 where L1.birthday = L2.birthday and L1.bioguide_id != L2.bioguide_id limit 10;

/*
4
Find all votes that have passed
*/
select * from Vote where result = 'PASSED' limit 10;

/*
5
How many legislators have there been for each party (using only a legislators most recent term to decide their party)
*/
select party, count(party) as count from Term join (select bioguide_id, max(start) as ms from Term GROUP BY bioguide_id) as latest ON Term.start = latest.ms and Term.bioguide_id = latest.bioguide_id GROUP BY party ORDER BY count DESC limit 10;

/*
6
join 3 tables
For a Bill find all congress members and how they voted on the latest vote for that bill
*/
select `bioguide_id`, `First Name`, `Last Name`, how_voted from (select * from Legislator natural join Legislator_Vote) as LV join Vote ON Vote.id = LV.Vote_id where Vote_id = (select id from Vote where Bill_id =  'hr899-113' and number = (select max(number) from Vote where Bill_id = 'hr899-113')) limit 10;

/*
7
join 3 tables
Find all bills sponsored by a legislator from NM and the name of the legislator that sponsored it.
*/
select `Last Name`, Bill_id  from ((select bioguide_id from Term where state ='NM') as t1 natural join Legislator) natural join Sponsor limit 10;

/*
8
Union
Union the shared fields from amendment and bill, represents all pieces of legislation in the database:
*/
select * from (select id, type, status, introduced_at as introduction_date, congress, number from Amendment) as t1 union (select id, type, status, introduction_date, congress, number from Bill) limit 10 limit 10;

/*
9
Group by
For a given vote count the number of yes votes for each party
*/
select party, count(party) as count from (select distinct bioguide_id, party from Legislator_Vote natural join Term where Vote_id = 'h136-115.2017' and how_voted='Yea') as yes GROUP BY party limit 10;

/*
10
Order by
List the members of congress for congress 115 and order by state
*/
select distinct `First Name`, `Last Name`, state from Legislator natural join Term where start >= (select begin from Congress where id = 115) and end <= (select end from Congress where id = 115) ORDER BY state limit 10;


/*
11
Distinct
Find all subjects for Congress 114 (would be useful for navigation in the web interface)
*/
select distinct subject from Subject join Bill ON Subject.Bill_id = Bill.id where congress = 114 limit 10;

/*12
Aggregate
Count the number of terms a member of congress has served (only if we have a record of them voting in our data)
*/
select bioguide_id, `First Name`, `Last Name`, Terms from (select bioguide_id, count(bioguide_id) as Terms from Term GROUP BY bioguide_id) as tm natural join Legislator where bioguide_id in (select bioguide_id from Legislator_Vote) ORDER BY Terms DESC limit 10;

/********************************************************************************/
/*modification*/
/********************************************************************************/
start transaction;
/*
1
Update
If a Legislator does not have a full name update it with their wikipedia id (which is a full name) only if wikipedia id is not null as well
*/
update Legislator set official_full_name=wikipedia_id where official_full_name IS NULL and wikipedia_id IS NOT NULL;

/*
2
Update
Change a legislators end date for a specific congress (like if they have been removed from office)
*/
update Term
       set end = '2017-05-01'
       	   where bioguide_id = (select bioguide_id from Legislator where `First Name` = 'Tom' and `Last Name` = 'Marino') and end > '2017-05-01';

/*
3
Deletion
Delete all Legislators , Sponsor, and Terms for those legislator if they never participated in a vote in our data
*/
delete from Term where bioguide_id not in (select bioguide_id from Legislator_Vote);
delete from Sponsor where Legislator_id not in (select bioguide_id from Legislator_Vote);
delete from Legislator where bioguide_id not in (select bioguide_id from Legislator_Vote);

/*
4
Deletion
Delete all votes that we do not have a record of Legislators voting on it
*/
delete from Vote where id not in (select Vote_id as id from Legislator_Vote);

/*
5
Insert
Add a new Legislator
*/
Insert into Legislator (bioguide_id, `Last Name`, `First Name`, birthday, gender, `wikipedia_id`, govtrack_id, official_full_name)     Value     ('E000298', 'Estes', 'Ron', '1956-07-19', 'M', NULL, 412735, NULL);

/*
6
Insert
Add a new term for that legislator
*/
insert into Term VALUE ('E000298', '2017-04-27', '2019-01-03', 'rep', 'KS', NULL, 4, 'Republican', 'h');

rollback;

/********************************************************************************/
/*useful indexes
/********************************************************************************/
/*1*/
select L1.birthday, L1.bioguide_id, L1.`First Name`, L1.`Last Name`, L2.bioguide_id, L2.`First Name`, L2.`Last Name` from Legislator L1, Legislator L2 where L1.birthday = L2.birthday and L1.bioguide_id != L2.bioguide_id;

create index bday on Legislator (birthday);
select L1.birthday, L1.bioguide_id, L1.`First Name`, L1.`Last Name`, L2.bioguide_id, L2.`First Name`, L2.`Last Name` from Legislator L1, Legislator L2 where L1.birthday = L2.birthday and L1.bioguide_id != L2.bioguide_id;

drop index bday on Legislator;

/*2*/
select L1.bioguide_id, L1.`First Name`, L1.`Last Name`,L2.bioguide_id, L2.`First Name`, L2.`Last Name` from Legislator L1, Legislator L2 where L1.`Last Name` = L2.`Last Name` and L1.bioguide_id != L2.bioguide_id;

create index lastName on Legislator (`Last Name`);
select L1.bioguide_id, L1.`First Name`, L1.`Last Name`,L2.bioguide_id, L2.`First Name`, L2.`Last Name` from Legislator L1, Legislator L2 where L1.`Last Name` = L2.`Last Name` and L1.bioguide_id != L2.bioguide_id;

drop index lastName on Legislator;

/*3*/
select distinct how_voted from Legislator_Vote;

create index voteindex on Legislator_Vote(how_voted);
select distinct how_voted from Legislator_Vote;

drop index voteindex on Legislator_Vote;
