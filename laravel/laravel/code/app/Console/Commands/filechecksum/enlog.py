#!/usr/bin/python
__author__ = "Vickyraj Chavan"
__date__ = "03-06-2019"
__last_updated__= "25 FEB 2020"
#========================== IMPORT ETC ==========================
import db,traceback
import sys, MySQLdb, os
from lite_logging import log
from datetime import datetime
import enconfig, ast, requests, json
# db connection
dbobj = db.mysql( )
log  = log()
# get the dynamic log file name
file_name = os.path.basename(__file__)
file_name = os.path.splitext(file_name)[0]
logfile = file_name+".log"
#config_data = {}
enconfig_ = {}
tid=1
result = {}

def error(dataarray):   
    try:    
        data = {}        
        if not enconfig_['error_reporting']:  
             return False  
        config_data = enconfig.config_data     
        url = config_data['en_sysconfig_api_url']+'/log/error'
        if 'function_name' in dataarray :
            data['function'] = dataarray['function_name']
        if 'functionality' in dataarray :
            data['functionality'] = dataarray['functionality']
        if 'command' in dataarray :
            data['command'] = dataarray['command']
        if 'parameters' in dataarray :
            data['parameters'] = dataarray['parameters']
        if 'error' in dataarray :
            data['error'] = dataarray['error']
        writelog(data,url)

    except Exception, e:
        data['data'] = ''
        data['message']= e
        data['status'] = 'error'
        log.d(tid,traceback.format_exc(),logfile)
        log.u(tid,"data: %s"%data,logfile)
        sys.exit()

def debug(dataarray):   
    try:    
        data = {}           
        debug_set =""
        if 'pod_debug' in  enconfig_:
            debug_set = enconfig_['pod_debug']
        else :
            debug_set = "n"

        if debug_set == "n":
            return False      
            
        url = config_data['en_sysconfig_api_url']+'/log/debug'
        if 'function_name' in dataarray :
            data['function'] = dataarray['function_name']
        if 'functionality' in dataarray :
            data['functionality'] = dataarray['functionality']
        if 'command' in dataarray :
            data['command'] = dataarray['command']
        if 'parameters' in dataarray :
            data['parameters'] = dataarray['parameters']
        if 'error' in dataarray :
            data['error'] = dataarray['error']
        writelog(data,url)

    except Exception, e:
        data['data'] = ''
        data['message']= e
        data['status'] = 'error'
        log.u(tid,"data:%s "%data,logfile)
        log.d(tid,traceback.format_exc(),logfile)
        sys.exit()
#   return $this->message;


def writelog(data,url) :
    try:      
        response = requests.post(url =url, data = data)
        resp = response.json()
        if resp: 
            if resp['status'] == 'success':
                log.s(tid,resp['message']['success'],logfile)
        else:
            log.u(tid,"Empty response from api",logfile)
    except Exception, e:
        data['data'] = ''
        data['message'] = e
        data['status'] = 'error'
        log.u(tid,"data:%s"%data,logfile)
        log.d(tid,traceback.format_exc(),logfile)
        sys.exit()
enconfig_ = enconfig.load_config(enconfig.config_data)
if enconfig_ == str(enconfig_):
     enconfig_ = json.loads(enconfig_)

