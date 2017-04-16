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
