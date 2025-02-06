#!/usr/bin/python
__author__ = "Nishant Faye"
__date__ = "12-05-2020"

import json, os
#, enmessage
config_path = "../../../../"
db_config = {}
def get_db_config():
    try:    
            config_file = open(config_path + ".env")
            config_data = config_file.readlines()
            for line in config_data:
                if not line.isspace():
                    y = line.split("=")
                    if y != "":
                        key = y[0].strip()
                        value = y[1].strip()
                    if key and value:
                        db_config[key]=value

            config_file.close()
            return db_config
    except Exception as e:
        print "get database configguration data failed"









