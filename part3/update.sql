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
