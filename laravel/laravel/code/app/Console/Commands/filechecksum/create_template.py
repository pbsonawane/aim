#!/usr/bin/python

__author__ = "Vickyraj Chavan"
__date__ = "20-11-2019"
__last_updated__by__= "Nishant Faye"
__last_updated__= "15-05-2020"

import os, sys, re, time, datetime
import hashlib, getpass, MySQLdb,requests
import db,json
from lite_logging import log
from get_db_config import get_db_config
import enmessage, enlog,enconfig
tid = '1'
db_config = get_db_config()
# db connection
dbobj = db.mysql()
log  = log()
# get the dynamic log file name
file_name = os.path.basename(__file__)
file_name = os.path.splitext(file_name)[0]
logfile = file_name+".log"
dataarray = {}
dataarray['functionality'] = "File Checksum Snapshot Create"
entity = 'File Checksum'

ts = time.time()
timestamp = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')

work_dir = os.path.dirname(os.path.realpath(__file__))

probe_id = "1" 
dbhost = db_config["DB_HOST"]
dbhost = dbhost.strip()
dbname = db_config['DB_DATABASE']
bname = dbname.strip()
dbuser = db_config['DB_USERNAME']
dbuser = dbuser.strip()
dbpass = db_config['DB_PASSWORD']
dbpass = dbpass.strip()


config_data = enconfig.get_config()
checksum_dir  = work_dir+"/"
if not os.path.isdir(checksum_dir):
    try:
        os.makedirs(checksum_dir)
    except OSError as e:
        if e.errno != errno.EEXIST:            
            msg_key = 'DIR_CREATE_FAILED'       
            data = enmessage.show(msg_key)
            log_msg = data['msg']           
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ]"
                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                enlog.error(dataarray)   
            raise(log_msg)

schema_filename = work_dir+"/dbschema_checklist.txt"
           
def getmd5checksum(file):
    try:
        dataarray['function_name'] = "getmd5checksum"
        if os.path.exists(file):
            f = open(file, 'r+')
            content = f.read()
            f.close()
            if content != "":
                md5 = hashlib.md5(content).hexdigest()
                if md5 != "":
                    return md5
            else:
                print "content is blank for %s"%(file)                
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


def create_template(dir):
    try:
        dataarray['function_name'] = "create_template"
        if not os.path.exists(dir):                     
            msg_key = 'DIR_NOT_EXIST'       
            data = enmessage.show(msg_key)
            log_msg = data['msg']       
            msg_code = data['code']
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ][ %s ]" %dir
                log.u(ts,"[ "+log_msg+" ][ %s ]" %dir,logfile)                                   
                enlog.error(dataarray)
            return    
        
        cmd = "which tree"
        tree_bin = os.popen(cmd).read()        
        tree_bin = tree_bin.strip()         
        
        if os.path.exists(tree_bin):
            tree_file = "/tmp/tree_struct.txt"              
            exclude_dir_path = work_dir+"/exclude_files.txt"            
            tree_command = tree_bin+" "+dir+" -i -f -p -u --noreport -o "+tree_file   #tree coommand
            is_success = os.system(tree_command)
            if is_success != 0:
                print "Error in retrieving tree"                                
                msg_key = 'TREE_CMD_FAILED'       
                data = enmessage.show(msg_key)
                log_msg = data['msg']           
                if log_msg:
                    dataarray['error'] = "[ "+log_msg+" ]"
                    log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                    enlog.error(dataarray)  
                    return
            tree_output = []
            if os.path.exists(tree_file):
                f = open(tree_file, 'r+')
                tree_output = f.read()
                f.close()
                tree_output = tree_output.split('\n')
                tree_output = filter(None,tree_output)            
                os.remove(tree_file)            
                
            exclude = []

            if os.path.exists(exclude_dir_path):
                f = open(exclude_dir_path, 'r+')
                exclude = f.read()
                f.close()
                exclude = exclude.split('\n')
                exclude = filter(None,exclude)
            else:
                print "exclude dir : %s does not exist!!!"%(exclude_dir_path)
            
            for file in tree_output:
                x = re.search("^\/", file) 
                if not x == "":             
                    chk_excluse = 0;            
                    string_check = file
                    string_check = re.sub('\[.*\]','',string_check)
                    string_check = string_check.strip()
                    for dir in exclude :
                            dir = dir.strip()
                            x = re.search(dir, string_check)                        
                            if x :
                                chk_excluse = 1                                
                                break                          
                    if chk_excluse == 0 :           
                        filename = string_check 
                        is_dir = os.path.isdir(filename)                                     
                        if os.path.exists(filename) and is_dir == False :
                                md5_checksum = getmd5checksum (filename)                                                            
                                if md5_checksum :
                                    filename = filename.encode('base64')     
                                    filename = filename.strip()  
                                    filename = filename.replace('\n','')                                
                                    # execute SQL query 
                                    url = config_data['en_sysconfig_api_url']+'/snapshot/add'
                                    data = {}
                                    data['file_path'] = filename
                                    data['md5'] = md5_checksum
                                    data['probe_id'] = probe_id
                                    data['version'] = "1"
                                    data['status'] = 'ok'  
                                    data['module'] = 'ITAM-APP'
                                   
                                    response = requests.post(url =url, data = data)
                                    if response.status_code == 200:
                                        resp = response.json()
                                        if resp:
                                                if resp['status'] == 'success':
                                                    filename = filename.decode('base64')
                                                    print "Added file snapshot : %s"%(filename)

                                                elif resp['status'] == 'error':
                                                    log.d(tid,resp['message'],logfile)
                                        else:
                                                msg_key = 'NO_API_RESPONSE'       
                                                data = enmessage.show(msg_key)
                                                log_msg = data['msg']           
                                                if log_msg:
                                                    dataarray['error'] = "[ "+log_msg+" ]"
                                                    log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                                                    enlog.error(dataarray)                                              
                                    else:                                 
                                        msg_key = 'NO_API_RESPONSE'       
                                        data = enmessage.show(msg_key)
                                        log_msg = data['msg']           
                                        if log_msg:
                                            dataarray['error'] = "[ "+log_msg+" ] [ %s ]"%(response)
                                            log.u(ts,"[ "+log_msg+" ] [ %s ]"%(response),logfile)                                   
                                            enlog.error(dataarray)                                          
                                else:
                                    print "skipping blank file :%s"%(filename)                                    
                        else:                            
                            msg_key = 'FC_FILE_DOES_NOT_EXIST'
                            data = enmessage.show(msg_key)
                            log_msg = data['msg']           
                            if log_msg:                                
                                log.u(ts,"[ "+log_msg+" ] [ %s ]"%(filename),logfile)                            
        else:
            print "tree utility is not installed on server!!!\n"            
            msg_key = 'TREE_CMD_FAILED'       
            data = enmessage.show(msg_key)
            log_msg = data['msg']           
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ]"
                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                enlog.error(dataarray) 
            sys.exit()
    except Exception as error:
        print(error)
        print('The create_template() function was not executed')        
        msg_key = 'TEMPLATE_FUNCTION_FAILED'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']       
        msg_code = data['code']
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
            log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile)    
            enlog.error(dataarray)   


def create_template_db():
    try:
        dataarray['function_name'] = "create_template_db"
        print "creating db schema template" 
        tmp_schema_file = "/tmp/schema_1"       
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
            md5_checksum = getmd5checksum (tmp_schema_file)
            if md5_checksum :
                # execute SQL query 
                schema_filename = work_dir+"/dbschema_checklist.txt"
                schema_filename = schema_filename.encode('base64')  
                schema_filename = schema_filename.replace('\n','')   
                url = config_data['en_sysconfig_api_url']+'/snapshot/add'
                data = {}
                data['file_path'] = schema_filename
                data['md5'] = md5_checksum
                data['probe_id'] = probe_id
                data['version'] = "1"
                data['status'] = 'ok'  
                data['module'] = 'ITAM-APP'
                                                    
                response = requests.post(url =url, data = data)
                if response.status_code == 200:
                    resp = response.json()
                    if resp: 
                        if resp['status'] == 'success':
                            print "Added file snapshot : %s"%(schema_filename)
                    else:
                        msg_key = 'NO_API_RESPONSE'       
                        data = enmessage.show(msg_key)
                        log_msg = data['msg']           
                        if log_msg:
                            dataarray['error'] = "[ "+log_msg+" ]"
                            log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                            enlog.error(dataarray)  
                else:
                    msg_key = 'NO_API_RESPONSE'       
                    data = enmessage.show(msg_key)
                    log_msg = data['msg']           
                    if log_msg:
                        dataarray['error'] = "[ "+log_msg+" ] [ %s ]"%(response)
                        log.u(ts,"[ "+log_msg+" ] [ %s ]"%(response),logfile)                                   
                        enlog.error(dataarray)  
            else:
                print "skipping blank file :%s"%(filename)
        if os.path.exists(tmp_schema_file):
           os.remove(tmp_schema_file)
    except Exception as error:
        print(error)
        print('The create_template_db() function was not executed')          
        msg_key = 'DBSCHEMA_FUNCTION_FAILED'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']       
        msg_code = data['code']
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
            log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile)  
            enlog.error(dataarray)        

try:       

    url = config_data['en_sysconfig_api_url']+'/snapshot/delete'
    data = {}     
    data['module'] = 'ITAM-APP'                                        
    response = requests.post(url =url, data = data)    
    if response.status_code == 200:
        resp = response.json()
        if resp: 
            if resp['status'] == 'success':
                log.s(ts,resp['message']['success'],logfile)
        else:
            msg_key = 'NO_API_RESPONSE'       
            data = enmessage.show(msg_key)
            log_msg = data['msg']           
            if log_msg:
                dataarray['error'] = "[ "+log_msg+" ]"
                log.u(ts,"[ "+log_msg+" ]",logfile)                                   
                enlog.error(dataarray) 
    else:
        msg_key = 'NO_API_RESPONSE'       
        data = enmessage.show(msg_key)
        log_msg = data['msg']           
        if log_msg:
            dataarray['error'] = "[ "+log_msg+" ] [ %s ]"%(response)
            log.u(ts,"[ "+log_msg+" ] [ %s ]"%(response),logfile)                                   
            enlog.error(dataarray)    
    work_dir_path = dbobj.select_dict("select configuration from en_system_settings where type='filechecksum' AND status='y'",None,True)
    dir_path = work_dir_path['configuration']
    if type(dir_path) == str:
        dir_path = json.loads(dir_path)
    dir_path = dir_path["en_work_dir"]
    create_template(dir_path)
    create_template_db()    
except Exception as error:
    print(error)
    print('The main() function was not executed')          
    msg_key = 'MAIN_FUNCTION_FAILED'       
    data = enmessage.show(msg_key)
    log_msg = data['msg']       
    msg_code = data['code']
    if log_msg:
        dataarray['error'] = "[ "+log_msg+" ][ %s ]" %error
        log.u(ts,"[ "+log_msg+" ][ %s ]" %error,logfile)                                   
        enlog.error(dataarray)
