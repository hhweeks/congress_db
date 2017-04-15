import sys
import os
import datetime
import yaml
import copy
import json

data_folder = sys.argv[1]

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

def copy_if_exists(key, df, dto):
    if key in df:
        dto[key] = str(copy.deepcopy(df[key]))

data = curr
data.extend(hist)

ids = []

states = []
district = {}
        
for leg in data:
    bio = leg['bio']
    entry = {}
    for k in ['gender', 'birthday']:
        copy_if_exists(k, bio, entry)
    legid = leg['id']
    for k in ['bioguide', 'govtrack']:
        entry[(k+'_id')] = copy.deepcopy(legid[k])
    if entry['bioguide_id'] in ids:
        continue
    else:
        ids.append(entry['bioguide_id'])
    if 'wikipedia' in legid:
        entry['wikipedia_id'] = copy.deepcopy(legid['wikipedia'])
    name = leg['name']
    copy_if_exists('official_full', name, entry)
    entry['first_name'] = name['first']
    entry['last_name'] = name['last']

    for k in entry:
        entry[k] = str(entry[k]).replace("'", "\\'")
    legislators.append(entry)

    for t in leg['terms']:
        term = {}
        term['bioguide_id'] = entry['bioguide_id']
        copy_if_exists('district', t, term)
        term['start'] = t['start']
        term['end'] = t['end']
        term['state'] = t['state']

        if t['state'] not in district:
            district[t['state']] = set()

        if 'district' in t:
            district[t['state']].add(t['district'])
        
        if 'party' in t:
            term['party'] = t['party']
        else:
            term['party'] = 'Unknown'
        term['type'] = t['type']
        if term['type'] == 'rep':
              term['chamber'] = 'h'
        elif term['type'] == 'sen':
              term['chamber'] = 's'
        else:
              print("Error expected type to be rep or sen: ", term['type'])
              sys.exit(-1)
        copy_if_exists('url', t, term)
        for k in term:
            term[k] = str(term[k]).replace("'", "\\'")
        terms.append(term)
        


sql = ''' INSERT INTO Legislator
    (`bioguide_id`, `Last Name`, `First Name`, `birthday`, `gender`, `wikipedia_id`, `govtrack_id`, `official_full_name`)
    VALUES
'''

def null_check_add(key, d):
    if key in d:
        return "'%s'" % d[key]
    else:
        return "Null"

for leg in legislators[:-1]:
    sql += "('"
    sql += leg['bioguide_id']
    sql += "','"
    sql += leg['last_name'].replace("'","\'")
    sql += "','"
    sql += leg['first_name']
    sql += "',"
    sql += null_check_add('birthday', leg)
    sql += ","
    sql += null_check_add('gender', leg)
    sql += ","
    sql += null_check_add('wikipedia_id', leg)
    sql += ",'"
    sql += str(leg['govtrack_id'])
    sql += "',"
    sql += null_check_add('official_full', leg).replace("'","\'")
    sql += "),\n"

leg = legislators[-1]
sql += "('"
sql += leg['bioguide_id']
sql += "','"
sql += leg['last_name']
sql += "','"
sql += leg['first_name']
sql += "',"
sql += null_check_add('birthday', leg)
sql += ","
sql += null_check_add('gender', leg)
sql += ","
sql += null_check_add('wikipedia_id', leg)
sql += ",'"
sql += str(leg['govtrack_id'])
sql += "',"
sql += null_check_add('official_full', leg)
sql += ");\n"

print("Writing to Legislators.sql")
f = open('Legislator.sql', 'w')
f.write(sql)
f.close()


sql = ''' INSERT INTO Term
    (`bioguide_id`, `start`, `end`, `type`, `state`, `url`, `district`, `party`, `chamber`)
    VALUES
'''

for term in terms[:-1]:
    sql += "('"
    sql += term['bioguide_id']
    sql += "','"
    sql += term['start']
    sql += "','"
    sql += term['end']
    sql += "','"
    sql += term['type']
    sql += "','"
    sql += term['state']
    sql += "',"
    sql += null_check_add('url', term)
    sql += ","
    sql += null_check_add('district', term)
    sql += ",'"
    sql += term['party']
    sql += "','"
    sql += term['chamber']
    sql += "'),\n"

term = terms[-1]
sql += "('"
sql += term['bioguide_id']
sql += "','"
sql += term['start']
sql += "','"
sql += term['end']
sql += "','"
sql += term['type']
sql += "','"
sql += term['state']
sql += "',"
sql += null_check_add('url', term)
sql += ","
sql += null_check_add('district', term)
sql += ",'"
sql += term['party']
sql += "','"
sql += term['chamber']
sql += "');\n"


print("Writing to Term.sql")
f = open('Term.sql', 'w')
f.write(sql)
f.close()

sql = ''' INSERT INTO District
    (`number`, `state`)
    VALUES
'''

keys = list(district.keys())

for state in keys[:-1]:
    ds = list(district[state])
    for d in ds:
        sql += "('"
        sql += str(d)
        sql += "','"
        sql += state
        sql += "'),\n"

state = keys[-1]
ds = list(district[state])
for d in ds[:-1]:
    sql += "('"
    sql += str(d)
    sql += "','"
    sql += state
    sql += "'),\n"

d = ds[-1]
sql += "('"
sql += str(d)
sql += "','"
sql += state
sql += "');\n"

print("Writing to District.sql")
f = open('District.sql', 'w')
f.write(sql)
f.close()

sql = ''' INSERT INTO State
    (`name`, `num_districts`)
    VALUES
'''

keys = list(district.keys())

for state in keys[:-1]:
    sql += "('"
    sql += state
    sql += "','"
    sql += str(len(list(filter(lambda x: x >= 0, district[state]))))
    sql += "'),\n"

state = keys[-1]
sql += "('"
sql += state
sql += "','"
sql += str(len(list(filter(lambda x: x >= 0, district[state]))))
sql += "');\n"

print("Writing to State.sql")
f = open('State.sql', 'w')
f.write(sql)
f.close()

sql = ''' INSERT INTO Chamber
    (`id`, `name`)
    VALUES
    ('s', 'Senate'),
    ('h', 'House of Representatives');
'''

print("Writing to Chamber.sql")
f = open('Chamber.sql', 'w')
f.write(sql)
f.close()

