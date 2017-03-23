import sys
import os
import datetime

data_folder = sys.argv[1]

session_file = os.path.join(data_folder,'us', 'sessions.tsv')

if not os.path.isfile(session_file):
    print "Error: expected file ", session_file
    sys.exit(-1)

f = open(session_file,'r')

sessions = [line.strip().split('\t') for line in f]

congress = {}

for line in sessions[1:]:
    if line[0] not in congress:
        congress[line[0]] = []
    congress[line[0]].append(datetime.datetime.strptime(line[2],'%Y-%m-%d'))
    congress[line[0]].append(datetime.datetime.strptime(line[3],'%Y-%m-%d'))

sql = ''' INSERT INTO Congress
    (id, begin, end)
    VALUES
 '''

congress_keys = congress.keys()

date_to_str = lambda x: x.isoformat().split('T')[0]

for k in congress_keys[0:-1]:
    sql += ('''('%s', '%s', '%s'),\n''' % (k, date_to_str(min(congress[k])), date_to_str(max(congress[k]))))

k = congress_keys[-1]
sql += ('''('%s', '%s', '%s');\n''' % (k, date_to_str(min(congress[k])), date_to_str(max(congress[k]))))

print sql
print '\n'
print '\n'        
    

sql = ''' INSERT INTO Session
    (congress, type, begin, end)
    VALUES
 '''
for line in sessions[1:-1]:
    sql += ('''('%s', '%s', '%s', '%s')''' % (line[0], line[1], line[2], line[3]))
    sql += ',\n'

line = sessions[-1]
sql += ('''('%s', '%s', '%s', '%s');\n''' % (line[0], line[1], line[2], line[3]))


print sql
