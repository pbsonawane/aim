#!/usr/bin/python
__author__ = "Vickyraj Chavan"
__date__ = "20-06-2019"
__last__update_by__ = "Nishant Faye"
__last_update_date__ = "19-05-2020"

import time
from get_db_config import get_db_config
db_config = get_db_config()
path = db_config['LOG_PATH']

class log:  

    def s(self,tid,msg,l_file):
        GREEN='\033[0;32m'
        NC='\033[0m' # No Color
        file = open(path + l_file, "a")
        file.write("\n%s[ %s ] [ %s ] :  %s  %s" %(GREEN,time.strftime("%c"),str(tid),msg,NC))
        file.close()        

    def u(self,tid,msg,l_file):

        RED='\033[0;31m'
        NC='\033[0m' # No Color
        file = open(path + l_file,"a")
        file.write("\n%s[ %s ] [ %s ] :  %s  %s" %(RED,time.strftime("%c"),str(tid),msg,NC))
        file.close()


    def d(self,tid,msg,l_file):
        NC='\033[0m' # No Color
        file = open(path + l_file,"a")
        file.write("\n%s[ %s ] [ %s ] :  %s  %s" %(NC,time.strftime("%c"),str(tid),msg,NC))
        file.close()
        

