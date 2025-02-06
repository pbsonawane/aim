__author__ = "Gaurav Jadhav"
__date__= "5 June 2019"
__last_updated__= "25 FEB 2020"
#--------------------------------------------------------------------------------------------------------------------------------------------
import db ,re, enconfig
import  sys, MySQLdb, os, requests, json, ast
from lite_logging import log
#--------------------------------------------------------------------------------------------------------------------------------------------
# db connection
dbobj = db.mysql()

log  = log()
# get the dynamic log file name
file_name = os.path.basename(__file__)
file_name = os.path.splitext(file_name)[0]
logfile = file_name+".log"

enmessages = 'enmessages'
config_data = {}
tid =1
#--------------------------------------------------------------------------------------------------------------------------------------------
#get details from system_settings and store it in dictionary
def load_message(config_data,message_type = ""):
	try:
		message_type = "all"
                config_data = enconfig.config_data
		from_ = config_data['en_sysconfig_config']
		from_ = from_ if from_ != '' else 'api'
		if from_ == "api" :
		    url = config_data['en_sysconfig_api_url']+'/message/get?message_type='+message_type
		    message = requests.get(url = url)
		    #check api response status
		    if message.status_code == 200:
		        resp =message.json()
		        if resp:
			    if resp['status'] == 'success':
			        log.s(tid,resp['message']['success'],logfile)
                                message = process_message(resp)
			    else:
			        log.u(tid,"Empty response from api",logfile)
		    else:
		    	log.u(tid, "Unexpected response from api : %s"%(resp),logfile)		    
		    	
		elif from_ == "local":	 
		    if not os.path.isdir(config_data['en_sysconfig_config_path']):
			try:
			    os.makedirs(config_data['en_sysconfig_config_path'])
			except OSError as e:
			    if e.errno != errno.EEXIST:
				raise("Unable to create system directory")
		    		
		    if os.path.exists(config_data['en_sysconfig_config_path']+"/"+enmessages):    
			f = open(config_data['en_sysconfig_config_path']+"/"+enmessages, "r")
			data = f.readline()
			if data != "" :
			    message = json.loads(data)
			    return message

		    else :
			url = config_data['en_sysconfig_api_url']+'/message/get?message_type='+message_type
			message = requests.get(url = url)
			message = message.json()
			if message :
			    message = process_message(message)
                            message = json.dumps(message)
			    try:                
				f = open(config_data['en_sysconfig_config_path']+"/"+enmessages, "a")
				f.write(str(message))
				f.close()
			    except IOError as x:
				if x.errno == errno.ENOENT:
				    log.d(tid,"Directory or file does not exist",logfile)                   
				elif x.errno == errno.EACCES:
				    log.d(tid,"Directory or file is not writable",logfile)                   
				    
				                   
			else:
			    log.d(tid,"No response from API",logfile)

		return message
	except Exception, e:
		log.d(tid,"Error in loading message %s"%e,logfile)

#--------------------------------------------------------------------------------------------------------------------------------------------				
def process_message(response):
	try:
		if isinstance(response, dict) and response['status'] == "error":        
		    response['message']['error']
		else:
		    if response['status'] == "success" :            
			content = response['data']
			return content
	except Exception, e:
                log.d(tid, "Error in processing message ",logfile)
#--------------------------------------------------------------------------------------------------------------------------------------------
def show(key, entity= ''):
	try:
            if key:
	        log.d(tid,"Log message function started key  : [ %s ]" %key,logfile)
            
            log.d(tid, "Calling load message ",logfile)
            message = load_message(config_data)
            if not message:
                log.u(tid,"getting message None from load message function ",logfile)
            if type(message) == str:
                    message = json.loads(message)

            #log.d = (tid, "load message response message : [%s]"%message,logfile)
	    data = {'code':'None','msg':'None'}
	    if message[key]:
	    	log.s(tid,"key found : [ %s ]" %key,logfile)
	        msg = message[key]
	        log.s(tid,"Message  : [ %s ]" %msg,logfile)
	    else:
	    	log.u(tid,"key not found : [ %s ]" %key,logfile)
	        msg = data
	        log.u(tid,"Message  : [ %s ]" %msg,logfile)
	        log.u(tid,"key not found : [ %s ]" %msg,logfile)

            if entity:
                msg1 = msg['msg']
                msg['msg'] = re.sub(r"{name}", entity, msg1)
                
            return msg 

        except KeyError:
            log.d(tid,"Either Key is Blank or No key Found ",logfile)
            pass
            msg = data 
            return msg
	except Exception, e:
	    log.u(tid,"Unexpected error: %s "%e,logfile)
            msg = data
       	    log.u(tid,"key not found : [ %s ]" %msg,logfile)
            return msg
#--------------------------------------------------------------------------------------------------------------------------------------------            
