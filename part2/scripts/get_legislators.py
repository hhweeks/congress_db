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
    entry['last_name'] = name['first']

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
        if 'party' in term:
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


