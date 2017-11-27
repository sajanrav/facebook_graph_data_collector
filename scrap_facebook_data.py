#This file makes a call using the Facebook graph api 
#and extracts the fields from the json object returned. 
#The extracted fields are used subsequently used to
#create a sql query to populate a database running 
#on the AWS RDS instance.

import urllib
import time
import datetime
import json
import MySQLdb as mdb

#Main function
def main():
	dateObj = " "
	query = " "
	
	while(1):
		fileStr = " "
		now = datetime.datetime.now()
		
		#Extracting the time stamp at which the data was obtained from Facebook
		dateObj = str(now.year) + "-" + str(now.month) + "-" + str(now.day) + " " + str(now.hour) + ":" + str(now.minute) + ":" + str(now.second)

		#Call to the Facebook Graph API to extract data representing Dunkin Donuts which returns a JSON object
		f = urllib.urlopen("https://graph.facebook.com/DunkinDonuts")

		#Create a python dictionary from the returned JSON object
		jObj = json.loads(f.read())

		query = developQuery(jObj,dateObj)
		print query
		insertData(query)			
		time.sleep(30)

#Function for developing a SQL query to insert 
#data obtained from Facebook.
def developQuery(tempJObj, datetime):
		query = "INSERT INTO dunkin_data VALUES(\"" + datetime + "\"," + str(tempJObj['id']) + ",\"" + tempJObj['name'] + "\",\"" + tempJObj['category'] + "\"," + str(tempJObj['likes']) + ")"
 		return query	

#Function making a connection to the Amazon EC2 
#RDS instance and loading data into the RDS instance
def insertData(query):
	con = None
	con = mdb.connect('#####', '#####', '#########', 'fb_scrap_data');
	try:
		with con:
			cur = con.cursor()
			cur.execute(query)		
	except:
		print "Error in Connection"
	finally:
		if con:
			con.close()
		
if __name__ == '__main__':
	 main()
