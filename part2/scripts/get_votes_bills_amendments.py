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
congresses = glob.glob(os.path.join(data_folder, "congress", "[0-9]*"))

print(congresses)

congresses = [congresses[-1]]

bill_order = ['id', 'type', 'title', 'popular_title', 'short_title', 'status', 'introduction_date', 'summary', 'congress', 'number']
subject_order = ['subject', 'Bill_id']
sponsor_order = ['Legislator_id', 'Bill_id']
legislator_vote_order =['Vote_id', 'bioguide_id', 'how_voted']
vote_order = ['id', 'chamber', 'category', 'question', 'congress', 'session', 'result', 'requires', 'number', 'date', 'type', 'Bill_id', 'Amendment_id']
amendment_order =['id', 'description', 'purpose', 'status', 'introduced_at', 'status_at', 'type', 'Bill_id', 'Amendment_id', 'congress', 'number']

bill_table = []
vote_table = []
amendment_table = []
sponsor_table = []
legislator_vote_table = []
subject_table = []

def str_if_exists(d, key):
    if key in d:
        return str(d[key]).replace("'", "\\'")
    else:
        return None

def str_key(d, key):
    return str(d[key]).replace("'", "\\'")

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
    else:
        amendment.append(None)
    amendment.append(str_key(d,'amendment_id'))
    amendment.append(str_key(d,'congress'))
    amendment.append(str_key(d,'number'))
    return amendment

def get_bill(d):
    bill = []
    bill.append(str_key(d, 'bill_id'))
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
        sp.append(ds['bioguide_id'])
        sp.append(d['bill_id'])
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
            amendment_table.append(get_amendment(data))
            

    sql = to_sql("Bill", bill_table, bill_order)
    f = open('Bill_' + congress_number + ".sql", "w")
    print("Writing to Bill_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    sql = to_sql("Sponsor", sponsor_table, sponsor_order)
    f = open('Sponsor_' + congress_number + ".sql", "w")
    print("Writing to Sponsor_" + congress_number + ".sql")
    f.write(sql)
    f.close()
    
    sql = to_sql("Subject", subject_table, subject_order)
    f = open('Subject_' + congress_number + ".sql", "w")
    print("Writing to Subject_" + congress_number + ".sql")
    f.write(sql)
    f.close()

    sql = to_sql("Amendment", amendment_table, amendment_order)
    f = open('Amendment_' + congress_number + ".sql", "w")
    print("Writing to Amendment_" + congress_number + ".sql")
    f.write(sql)
    f.close()


 
                       
