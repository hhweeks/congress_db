import sys
import os
import datetime
import yaml
import copy
import json
import glob
import MySQLdb

data_folder = sys.argv[1]
print(data_folder)

# Repeated work here to match id's but short on time
historical_file = os.path.join(data_folder, 'congress-legislators', 'legislators-historical.yaml')
current_file = os.path.join(data_folder, 'congress-legislators', 'legislators-current.yaml')

if not os.path.isfile(historical_file):
    print("Error: expected file ", historical_file)
    sys.exit(-1)

if not os.path.isfile(current_file):
    print("Error: expected file ", current_file)
    sys.exit(-1)
    
stream_hist = open(historical_file,'r')
stream_curr = open(current_file,'r')

hist = yaml.load(stream_hist, Loader=yaml.CLoader)
curr = yaml.load(stream_curr, Loader=yaml.CLoader)

legislators = []
terms = []

data = curr
data.extend(hist)

leg_ids = set()
bill_ids = set()

for leg in data:
    leg_ids.add(leg['id']['bioguide'])

congresses = glob.glob(os.path.join(data_folder, "congress", "[0-9]*"))

print(congresses)

bill_order = ['id', 'type', 'title', 'popular_title', 'short_title', 'status', 'introduction_date', 'summary', 'congress', 'number']
subject_order = ['subject', 'Bill_id']
sponsor_order = ['Legislator_id', 'Bill_id']
legislator_vote_order =['Vote_id', 'bioguide_id', 'how_voted']
vote_order = ['id', 'chamber', 'category', 'question', 'congress', 'session', 'result', 'requires', 'number', 'date', 'type', 'Bill_id', 'Amendment_id']
amendment_order =['id', 'description', 'purpose', 'status', 'introduced_at', 'status_at', 'type', 'Bill_id', 'Amendment_id', 'congress', 'number']


def str_if_exists(d, key):
    if key in d:
        return str(d[key]).replace("'", "\\'")
    else:
        return None

def str_key(d, key):
    return str(d[key]).replace("'", "\\'")

def get_legislator_votes(d):
    legislator_votes = []
    keys = list(d['votes'].keys())
    for k in d['votes']:
        voters = d['votes'][k]
        for v in voters:
            vote = []
            vote.append(str_key(d, 'vote_id'))
            if 'id' in v:
                # crud check if id is one we know
                if v['id'] in leg_ids:
                    vote.append(str_key(v, 'id'))
                    vote.append(str(k))
                    legislator_votes.append(vote)
    return legislator_votes

def get_vote(d):
    vote = []
    vote.append(str_key(d, 'vote_id'))
    vote.append(str_key(d,'chamber'))
    vote.append(str_key(d,'category'))
    vote.append(str_key(d,'question'))
    vote.append(str_key(d,'congress'))
    vote.append(str_key(d,'session'))
    vote.append(str_key(d,'result'))
    vote.append(str_key(d,'requires'))
    vote.append(str_key(d,'number'))
    vote.append(str_key(d,'date').split('T')[0])
    vote.append(str_key(d,'type'))
    if 'bill' in d:
        b = d['bill']
        vote.append(b['type'] + str(b['number']) + "-" + str(b['congress']))
    else:
        vote.append(None)
    if 'amendment' in d:
        b = d['amendment']
        vote.append(b['type'] + str(b['number']) + "-" + str(d['congress']))
    else:
        vote.append(None)
    return vote

def get_amendment(d):
    amendment = []
    amendment.append(str_key(d, 'amendment_id'))
    amendment.append(str_key(d,'description'))
    amendment.append(str_key(d,'purpose'))
    amendment.append(str_key(d,'status'))
    amendment.append(str_key(d,'introduced_at'))
    amendment.append(str_key(d,'status_at').split('T')[0])
    amendment.append(str_key(d,'amendment_type'))
    if 'amends_bill' in d and d['amends_bill'] and 'bill_id' in d['amends_bill']:
        amendment.append(str_key(d['amends_bill'],'bill_id'))
        if d['amends_bill']['bill_id'] not in bill_ids:
            return None
    else:
        amendment.append(None)
    amendment.append(str_key(d,'amendment_id'))
    amendment.append(str_key(d,'congress'))
    amendment.append(str_key(d,'number'))
    return amendment

def get_bill(d):
    bill = []
    bill.append(d['bill_id'])
    bill_ids.add(d['bill_id'])
    bill.append(str_key(d,'bill_type'))
    bill.append(str_key(d,'official_title'))
    bill.append(str_if_exists(d,'popular_title'))
    bill.append(str_if_exists(d,'short_title'))
    bill.append(str_key(d,'status'))
    bill.append(str_key(d,'introduced_at'))
    if 'summary' in d and d['summary'] and 'text' in d['summary']:
        bill.append(d['summary']['text'].replace('"',r'\"').replace("'", r"\'"))
    else:
        bill.append(None)
    bill.append(str_key(d,'congress'))
    bill.append(str_key(d,'number'))
    return bill

def get_sponsor(d):
    sp = []
    if 'sponsor' in d:
        ds = d['sponsor']
        if ds and 'bioguide_id' in ds:
            sp.append(ds['bioguide_id'])
            sp.append(d['bill_id'])
            if d['bill_id'] not in bill_ids:
                return None
        else:
            return None
        return sp
    return None

def get_subjects(d):
    sb = []
    for i in d['subjects']:
        e = []
        e.append(str(i).replace("'", "\\'"))
        e.append(d['bill_id'])
        sb.append(e)
    return sb

def to_sql(name, table, order):
    sql = '''INSERT INTO '''
    sql += name
    sql += "\n"
    sql += "(`"
    sql += '`,`'.join(order)
    sql += "`)"
    sql += "\n"
    sql += "VALUES\n"
    for i in range(len(table)):
        sql += "("
        for j in range(len(table[i])):
            v = table[i][j]
            if v:
                sql += "'"
                sql += v
                sql += "'"
            else:
                sql += 'Null'
            if j+1 < len(table[i]):
                sql += ","
        sql += ")"
        if i+1 < len(table):
            sql += ",\n"
        else:
            sql += ";\n"
    return sql
    
    
for congress in congresses:
    bill_table = []
    vote_table = []
    amendment_table = []
    sponsor_table = []
    legislator_vote_table = []
    subject_table = []

    congress_number = os.path.split(congress)[1]
    bill_datas = glob.glob(os.path.join(congress, "bills", "*", "*", "data.json"))
    vote_datas = glob.glob(os.path.join(congress, "votes", "*", "*", "data.json"))
    amendment_datas = glob.glob(os.path.join(congress, "amendments", "*", "*", "data.json"))
    for bill in bill_datas:
        with open(bill) as f:
            data = json.load(f)
            bill_table.append(get_bill(data))
            s = get_sponsor(data)
            if s:
                sponsor_table.append(s)
            subject_table.extend(get_subjects(data))

    for amendment in amendment_datas:
        with open(amendment) as f:
            data = json.load(f)
            a = get_amendment(data)
            if a:
                amendment_table.append(a)
            

    for vote in vote_datas:
        with open(vote) as f:
            data = json.load(f)
            vote_table.append(get_vote(data))
            legislator_vote_table.extend(get_legislator_votes(data))

    print("billtable ", len(bill_table))
    print("amendmenttable ", len(amendment_table))

    sql = ""
    for i in range(0, len(bill_table), 500):
        sql += to_sql("Bill", bill_table[i:(i+500)], bill_order)
        sql += "\n\n"
    f = open('Bill_' + congress_number + ".sql", "w")
    print("Writing to Bill_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    if len(sponsor_table) > 0:
        sql = ""
        for i in range(0, len(sponsor_table), 500):
            sql += to_sql("Sponsor", sponsor_table[i:(i+500)], sponsor_order)
            sql += "\n\n"
        f = open('Sponsor_' + congress_number + ".sql", "w")
        print("Writing to Sponsor_" + congress_number + ".sql")
        f.write(sql)
        f.close()

    sql = ""
    for i in range(0, len(subject_table), 500):
        sql += to_sql("Subject", subject_table[i:(i+500)], subject_order)
        sql += "\n\n"
    f = open('Subject_' + congress_number + ".sql", "w")
    print("Writing to Subject_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    sql = ""
    for i in range(0, len(amendment_table), 500):
        sql += to_sql("Amendment", amendment_table[i:(i+500)], amendment_order)
        sql += "\n\n"
    f = open('Amendment_' + congress_number + ".sql", "w")
    print("Writing to Amendment_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    sql = to_sql("Amendment", amendment_table, amendment_order)
    f = open('Amendment_' + congress_number + ".sql", "w")
    print("Writing to Amendment_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    sql = ""
    for i in range(0, len(vote_table), 500):
        sql += to_sql("Vote", vote_table[i:(i+500)], vote_order)
        sql += "\n\n"
    f = open('Vote_' + congress_number + ".sql", "w")
    print("Writing to Vote_" + congress_number + ".sql")
    f.write(sql)
    f.close()
    sql = ""

    for i in range(0, len(legislator_vote_table), 500):
        sql += to_sql("Legislator_Vote", legislator_vote_table[i:(i+500)], legislator_vote_order)
        sql += "\n\n"
    f = open('Legislator_Vote_' + congress_number + ".sql", "w")
    print("Writing to Legislator_Vote_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    

 
                       
