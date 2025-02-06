#!/usr/bin/python
__author__ = "Vickyraj Chavan"
__date__ = "03-06-2019"
__last_updated__= "25 FEB 2020"
#========================== IMPORT ETC ==========================
import db
import  sys, MySQLdb, os, requests, json, ast
from lite_logging import log

# db connection
dbobj = db.mysql()

log  = log()
# get the dynamic log file name
file_name = os.path.basename(__file__)
file_name = os.path.splitext(file_name)[0]
logfile = file_name+".log"
tid =1
ensysconfig = 'ensysconfig'
global config_data
config_data = {}
dataarray = {}
dataarray['functionality'] = "enconfig"
#get details from system_settings and store it in dictionary
def get_config():
    try:
        dataarray['function_name'] = "get_config"
        config_ = dbobj.select_dict("select configuration from en_system_settings where type='ensysconfig' AND status='y'",None,True)    
        conf_data =  config_["configuration"]
        config_data = json.loads(conf_data)
        return config_data
    except Exception, e:
	log.u(tid, "[ Error in system configuration : %s]" %e ,logfile)
        sys.exit() 


def load_config(config_data,config_item = ""):    
    try:
    	dataarray['function_name'] = "load_config"
        config_item = "all"
        from_ = config_data['en_sysconfig_config']
        from_ = from_ if from_ != '' else 'api'
        if from_ == "api" :           
            url = config_data['en_sysconfig_api_url']+'/config/get?config_item='+config_item
            config_data_ = requests.get(url = url)
            config_data_ = config_data_.json()
            if config_data_ :
                config_data_ = process_config(config_data_)
            else:
		log.u(tid,"No response from API",logfile)
                sys.exit("No response from API")
        elif from_ == "local":                    
            if not os.path.isdir(config_data['en_sysconfig_config_path']):
                try:
                    os.makedirs(config_data['en_sysconfig_config_path'])
                except OSError as e:
                    if e.errno != errno.EEXIST:
                    	log.u(tid,"Unable to create system directory",logfile)
                        raise("Unable to create system directory")
            if os.path.exists(config_data['en_sysconfig_config_path']+"/"+ensysconfig):    
                f = open(config_data['en_sysconfig_config_path']+"/"+ensysconfig, "r")
                data = f.readline()
                if data != "" :
                    data = json.dumps(data)
                    data = ast.literal_eval(data)
                    return data         
            else :       
                url = config_data['en_sysconfig_api_url']+'/config/get?config_item='+config_item
                config_data_ = requests.get(url = url)
                if config_data_ :
                    config_data_ = config_data_.json()
                    config_data_ = process_config(config_data_)
                    config_data_ = json.dumps(config_data_)
                    try:                
                        f = open(config_data['en_sysconfig_config_path']+"/"+ensysconfig, "a")
                        f.write(str(config_data_))
                        f.close()
                    except IOError as x:
                        if x.errno == errno.ENOENT:
				log.u(tid,"Directory or file does not exist : %s " %x,logfile)                              
				sys.exit("Directory or file does not exist => "+config_data['en_sysconfig_config_path']+"/"+ensysconfig)
				
                        elif x.errno == errno.EACCES:   
				log.u(tid,"Directory or file is not writable :%s " %x,logfile)         
                            	sys.exit("Directory or file is not writable => "+config_data['en_sysconfig_config_path']+"/"+ensysconfig)
                        else:
                        	sys.exit(argv[1], '- some other error')                
                else:
			log.u(tid,"No response from API",logfile)
		        sys.exit("No response from API")
        config_data_ = json.dumps(config_data_)
        return config_data_
    except Exception, e:
		log.u(tid,"Error in loading message: %s " %e,logfile)
       		sys.exit()          

def process_config(response):
    try:
        dataarray['function_name'] = "process_config"
        if isinstance(response, dict) and response['status'] == "error":        
            sys.exit(response['message']['error'])        
        else:
            if response['status'] == "success" :            
                content = response['data']
                return content

    except Exception, e:
    	log.u(tid,"Error in processing configuration: %s " %e,logfile)
    	sys.exit() 

config_data = get_config()
#get_config()
