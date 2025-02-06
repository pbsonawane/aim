#!/usr/bin/python
__author__ = "Vickyraj Chavan"
__date__ = "20-11-2019"
__last_updated__by__= "Nishant Faye"
__last_updated__= "14-05-2020"

import os, sys,re
import hashlib, MySQLdb, time, datetime
import db
from lite_logging import log
from get_db_config import get_db_config
import enmessage, enlog, requests, enconfig

work_dir = os.path.dirname(os.path.realpath(__file__))
ts = time.time()
timestamp = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')

# db connection
dbobj = db.mysql()
log  = log()
# get the dynamic log file name
file_name = os.path.basename(__file__)
file_name = os.path.splitext(file_name)[0]
logfile = file_name+".log"
dataarray = {}
dataarray['functionality'] = "File Checksum Snapshot Compare"
entity = 'File Checksum'
db_config = get_db_config()

probe_id = "1" 
dbhost = db_config["DB_HOST"]
dbhost = dbhost.strip()
dbname = db_config['DB_DATABASE']
bname = dbname.strip()
dbuser = db_config['DB_USERNAME']
dbuser = dbuser.strip()
dbpass = db_config['DB_PASSWORD']
dbpass = dbpass.strip()

config_data = {}
       
config_data = enconfig.get_config()    

def api_call(url, data):
    response = requests.post(url =url, data = data)
    if response.status_code == 200:
        resp = response.json()
        if resp: 
            if resp['status'] == 'success':
                return resp
        else:
            msg_key = 'NO_API_RESPONSE'       
            data = enmessage.show(msg_key)
            log_msg = data['msg']           
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ]"
                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                enlog.error(dataarray) 
            return False 
    else:
        msg_key = 'NO_API_RESPONSE'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']           
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ] [ %s ]"%(response)
            log.u(ts,"[ "+log_msg+" ] [ %s ]"%(response),logfile)                                   
            enlog.error(dataarray) 
        return False            

def getmd5checksum(file):
    try:    
        if os.path.exists(file):
            f = open(file, 'r+')
            content = f.read()
            f.close()
            if content != "":
                md5 = hashlib.md5(content).hexdigest()
                if md5 != "":
                    return md5
            else:
                print "content is blank for {}".format(file)
                msg_key = 'BLANK_CONTENT'       
                data = enmessage.show(msg_key)
                log_msg = data['msg']           
                if log_msg:                    
                    log.u(ts,log_msg%(file),logfile)                                                        
                return ""
    except Exception as error:
        print(error)
        print('The getmd5checksum() function was not executed') 
        msg_key = 'MD5CHECKSUM_FAILED'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']               
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
            log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile)                                   
            enlog.error(dataarray)  
        
def compare_template():
    try:    
        missing_count = 0
        modified_count = 0              
        schema_filename = work_dir+"/dbschema_checklist.txt"
        url = config_data['en_sysconfig_api_url']+'/snapshot/get'        
        data = {}
        data['probe_id'] = probe_id
        data['module'] = 'ITAM-APP'                                            
        resp = api_call(url, data)           
        records = resp['data']
        if records:
            check_flag =0
            for record in records:                
                filename = record['file_path']
                md5checksum = record['md5']
                filename = filename.strip()             
                filename = filename.decode('base64')
                md5checksum = md5checksum.strip()                
                if filename != schema_filename:                                  
                    url = config_data['en_sysconfig_api_url']+'/snapshot/update'
                    data = {}                    
                    data['md5'] = md5checksum
                    data['probe_id'] = probe_id
                    data['module'] = 'ITAM-APP'   
                    if not os.path.exists(filename):
                        print "Add|%s|Add the mentioned file\n"%(filename)                          
                        filename = filename.encode('base64')                                
                        filename = filename.replace('\n','')
                        data['file_path'] = filename
                        data['status'] = 'deleted'
                        resp = api_call(url, data)                        
                        check_flag =1       
                        missing_count +=1                       
                    else:       
                        # file md5checksum
                        curr_md5checksum = getmd5checksum (filename)
                        if curr_md5checksum :
                            if md5checksum != curr_md5checksum:
                                print "Modify %s|%s is mismatched !!! \n"%(filename,md5checksum)
                                filename = filename.encode('base64')                                
                                filename = filename.replace('\n','')
                                data['file_path'] = filename
                                data['status'] = 'modified'
                                resp = api_call(url, data)                                   
                                check_flag =1
                                modified_count +=1
                        else:
                            print "Unable to get latest md5 checksum"
                            msg_key = 'CHEKCSUM_GET_FAILED'                     
                            data = enmessage.show(msg_key)
                            log_msg = data['msg']           
                            if log_msg:
                                dataarray['error'] = "[ "+log_msg+" ]"
                                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                                enlog.error(dataarray) 
                else:
                    print "Skipping DB file in file compare"
        else:
            print "Template does not exist!! Nothing to compare!!\n"
            create_snapshot  = "python "+work_dir+"/create_template.py"
            os.system(create_snapshot)
            sys.exit()
            
        if check_flag == 0 :
            print "Everything is as per Snapshot!!!\n"  
            msg_key = 'FC_SNAPHSOT_OK'
            data = enmessage.show(msg_key)
            log_msg = data['msg']           
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ]"
                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                enlog.error(dataarray) 
            return ('n', missing_count, modified_count)     
        else:               
            return ('y', missing_count, modified_count)             

    except Exception as error:
        print(error)
        print('The compare_template() function was not executed')
        msg_key = 'FC_TEMPLATE_FUNCTION_FAILED'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']       
        msg_code = data['code']
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
            log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile) 
            enlog.error(dataarray) 

def compare_template_db():
    try:
        tmp_schema_file = "/tmp/schema"                 
        cmd = 'mysqldump -h '+dbhost+' -u '+dbuser+' -p'+"'"+dbpass+"'"+' --no-data '+dbname+" --skip-dump-date | sed 's/ AUTO_INCREMENT=[0-9]*//g'>"+tmp_schema_file
        is_error = os.system(cmd)
        if is_error != 0:
            print "error while retrieving database schema"          
            msg_key = 'DB_SCHEMA_FAILED'       
            data = enmessage.show(msg_key)
            log_msg = data['msg']           
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ]"
                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                enlog.error(dataarray)           
            sys.exit()
        else:
            curr_md5checksum = getmd5checksum (tmp_schema_file)  
            curr_md5checksum = curr_md5checksum.strip()
            schema_filename = work_dir+"/dbschema_checklist.txt"
            schema_filename = schema_filename.encode('base64')  
            schema_filename = schema_filename.strip()
            schema_filename = schema_filename.replace('\n','')
            url = config_data['en_sysconfig_api_url']+'/snapshot/get'            
            data = {}
            data['file_path'] = schema_filename
            data['md5'] = curr_md5checksum
            data['probe_id'] = probe_id
            data['module'] = 'ITAM-APP'            
            resp = api_call(url, data)  
            if resp: 
                if resp['status'] == 'success':
                    data = resp['data']
                    if data:                        
                        print "DB schema is as per snapshot"
                        msg_key = 'DB_SCHEMA_OK'       
                        data = enmessage.show(msg_key)
                        log_msg = data['msg']           
                        if log_msg:                 
                            log.d(ts,"[ "+log_msg+" ]",logfile)                                                 
                        return 'n'
                    else:
                        print "Modified DB schema template is mismatched !!! \n"                
                        msg_key = 'DB_SCHEMA_MODIFIED'       
                        data = enmessage.show(msg_key)
                        log_msg = data['msg']           
                        if log_msg:
                            dataarray['error'] = "[ "+log_msg+" ]"
                            log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                            enlog.error(dataarray)      
                        return 'y'
            else:
                msg_key = 'NO_API_RESPONSE'       
                data = enmessage.show(msg_key)
                log_msg = data['msg']           
                if log_msg:
                    dataarray['error'] = "[ "+log_msg+" ] [ %s ]"%(response)
                    log.u(ts,"[ "+log_msg+" ] [ %s ]"%(response),logfile)                                   
                    enlog.error(dataarray)              

    except Exception as error:
        print(error)
        print('The compare_template_db() function was not executed')    
        msg_key = 'DBSCHEMA_COMP_FUNCTION_FAILED'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']               
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
            log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile)  
            enlog.error(dataarray) 
        

try:                
    (file_status, missing_count, modified_count) = compare_template()       
    db_status = compare_template_db()
    if missing_count != 0 or modified_count !=0 :                       
        msg_key = 'CHECKSUM_MESSAGE'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']           
        if log_msg:                
            log.u(ts,log_msg%(modified_count,missing_count),logfile)                                                   
        comment = log_msg%(modified_count,missing_count)
    else:
        comment = ""        

    url = config_data['en_sysconfig_api_url']+'/filechange'
    data = {}
    data['file_status'] = file_status
    data['db_status'] = db_status
    data['probe_id'] = probe_id
    data['comment'] = comment         
    data['module'] = 'ITAM-APP'
    resp = api_call(url, data)
    if resp: 
        if resp['status'] == 'success':
            print "Record Upgraded"        
    else:
        msg_key = 'NO_API_RESPONSE'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']           
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ] [ %s ]"%(response)
            log.u(ts,"[ "+log_msg+" ] [ %s ]"%(response),logfile)                                   
            enlog.error(dataarray)  

except Exception as error:
    print(error)
    print('The main() function was not executed')
    msg_key = 'MAIN_FUNCTION_FAILED'       
    data = enmessage.show(msg_key)
    log_msg = data['msg']               
    if log_msg:
        dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
        log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile)                                   
        enlog.error(dataarray)
        
