#!/usr/bin/python
__author__ = "Vickyraj Chavan"
__date__ = "20-11-2019"
__last_updated__by__= "Nishant Faye"
__last_updated__= "14-05-2020"
#=================================================== IMPORT ETC ===================================================

import MySQLdb
from get_db_config import get_db_config
import time, os
i = 3

db_config = get_db_config()

path = db_config['LOG_PATH']
db_host = db_config["DB_HOST"]
dbname = db_config['DB_DATABASE']
dbname = dbname.strip()
dbuser = db_config['DB_USERNAME']
dbuser = dbuser.strip()
dbpass = db_config['DB_PASSWORD']
dbpass = dbpass.strip()

#===================================================== CLASSES =====================================================

class mysql:

    def write_log(self,msg):
        tid = 0
        NC='\033[0m'
        file = open(path + "db.log", "a")
        file.write("\n%s[ %s ] [ %s ] :  %s  %s" %(NC,time.strftime("%c"),str(tid),msg,NC))
        file.close()

    def getcon(self,host=db_host,uname=dbuser,passwd=dbpass,database=dbname):
        self.host = host
        self.uname = uname
        self.passwd = passwd
        self.database = database
        return MySQLdb.connect(host,uname,passwd,database)


    def select(self,uquery, params=None):       
        try:
                self.db = self.getcon()
                self.uquery = uquery
                cur = self.db.cursor()
                cur.execute(uquery,params)
                result = cur.fetchall()
                cur.close()
                self.db.close()
                return result
        except Exception, e:
                self.write_log("[ select : %s ] [ Error : %s ]"%(str(e)))

    def insert(self,uquery,params=None):
        try:
                self.db = self.getcon()
                self.uquery = uquery
                cur = self.db.cursor()
                cur.execute(uquery,params)
                cur.close()
                self.db.commit()
                self.db.close()
        except Exception, e:
                self.write_log("[ insert : %s ] [ Error : %s ]"%(str(uquery),str(e)))
                return e

    def select_dict(self, uquery, params=None, is_single=False):
        try:
                self.db = self.getcon()
                self.uquery = uquery
                cur = self.db.cursor(MySQLdb.cursors.DictCursor)
                cur.execute(uquery,params)
                result = cur.fetchall()
                cur.close()
                self.db.close()
                if is_single:
                    if result:
                        return result[0]
                    return None
                return result
        except Exception, e:
            self.write_log("[ select_dict : %s ] [ Error : %s ]"%(str(uquery),str(e)))

    def update(self,uquery,params=None):
        try:
                self.db = self.getcon()
                self.uquery = uquery
                cur = self.db.cursor()
                cur.execute(uquery,params)
                cur.close()
                self.db.commit()
                self.db.close()
            #except (AttributeError, MySQLdb.OperationalError):
        except Exception, e:
            self.write_log("[ insert : %s ] [ Error : %s ]"%(str(uquery),str(e)))


    def delete(self,uquery, params=None):
        #self.write_log("[ delete : %s ]"%str(uquery))
        try:
                self.db = self.getcon()
                self.uquery = uquery
                cur = self.db.cursor()
                cur.execute(uquery,params)
                cur.close()
                self.db.commit()
                self.db.close()
        except Exception, e:
            self.write_log("[ delete : %s ] [ Error : %s ]"%(str(uquery),str(e)))


