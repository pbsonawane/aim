<?php
$notifications = $notificationdata;
if (is_array($notifications) && count($notifications) > 0)
{
	//sort by 'created_at'
	$created_at = array_column($notifications, 'created_at');
	array_multisort($created_at, SORT_DESC, $notifications);

	foreach($notifications as  $notification)
	{	
        $notificationid = isset($notification['notification_id']) ? $notification['notification_id'] : '';
        $import_title	= '';
		$import_tot		= '';
		$import_success	= '';
		$import_fail	= '';
		$import_date	= '';
		
        if(isset($notification['report_name']) && $notification['report_name']) $report_name = $notification['report_name'];
        elseif(isset($notification['import_name']) && $notification['import_name']) $report_name = $notification['import_name'];
        else $report_name = '';

		if(isset($notification['notification_type']) && $notification['notification_type'] =='report') $notify_icon = 'fa fa-file-pdf-o';
        elseif(isset($notification['notification_type']) && $notification['notification_type'] =='import_asset'){
			$notify_icon = 'fa fa-arrow-circle-o-down';
			if(isset($notification['result'])){
				$res = $notification['result'];
				if($res){
					$res = json_decode($res,true);
					if(isset($res['total']))  $import_tot = $res['total'];
					if(isset($res['import'])) $import_success = $res['import'];
					if(isset($res['failed'])) $import_fail = $res['failed'];
				}
			}
			if(isset($notification['importdata'])){
				$res1 = $notification['importdata'];
				if($res1){
					$res1 = json_decode($res1,true);
					if(isset($res1['cititle']))  $import_title = $res1['cititle'];
				}
			}
			$import_date = isset($notification['created_at']) ? $notification['created_at'] : '';
			if($import_date != '') $import_date = date('d/m/Y H:i:s',strtotime($import_date));
		}
        else $notify_icon = 'fa fa-bookmark-o ';

		$icon ="fa fa-times"; 
		$downloadIcon = "N";
		if (isset($notification['status']) && $notification['status'] == "y") 
		{
			$icon ="fa fa-check";
			$downloadIcon = "Y";
		}
		else
		{
			$icon ="fa fa-refresh";
			$downloadIcon = "N";
		}
?>
	<li class="br-t of-h"> 
		<div class="fw600 p12 animated animated-short fadeInDown">
		
			<div class="row">
				<div class="col-md-1">
					<span class="{{$icon}} pr5"></span> 
				</div>
				<div class="col-md-5">
				<i class="<?php echo $notify_icon;?>" aria-hidden="true"></i> <span title="{{ isset($report_name) ? $report_name : '' }}">{{ isset($report_name) ?  substr($report_name, 0, 11) . '...' : '' }}</span>
				</div>
				<div class="col-md-5">
				<?php  if($notification['notification_type'] =='report' && $downloadIcon=="Y") { ?>
					<span class = "download_report text-primary" notification_type="{{ isset($notification['notification_type']) ? $notification['notification_type'] : '' }}" notification_id="{{ isset($notificationid) ? $notificationid : '' }}" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload");?>" report_name ="{{ isset($report_name) ? $report_name : '' }}"><i class="fa fa-download" aria-hidden="true"></i>
						&nbsp; <?php echo trans("label.download");?></span>
				<?php } elseif($notification['notification_type'] =='import_asset'){ ?>
					<span class = "text-primary notification_modal_open" notification_type="{{ isset($notification['notification_type']) ? $notification['notification_type'] : '' }}" notification_id="{{ isset($notificationid) ? $notificationid : '' }}" created_at="{{ isset($import_date) ? $import_date : ''}}" total="{{ isset($import_tot) ? $import_tot : ''}}"  success="{{ isset($import_success) ? $import_success : ''}}" fail="{{ isset($import_fail) ? $import_fail : ''}}"  import_title="{{ isset($import_title) ? $import_title : ''}}" style="cursor:pointer;" title="<?php echo trans("label.lbl_information");?>" report_name ="{{ isset($report_name) ? $report_name : '' }}" data-toggle="modal" data-target="#notification_modal"><i class="fa fa-info-circle" aria-hidden="true" ></i>
						&nbsp; <?php echo trans("label.lbl_information");?></span>
				<? }else{
					echo "-";
				}?>
				</div>
				<div class="col-md-1">
					<span class = "read_notification" style="color:red" notification_id="{{ isset($notificationid) ? $notificationid : '' }}" notification_type="{{ isset($notification['notification_type']) ? $notification['notification_type'] : '' }}" style="cursor:pointer;" ><i class="fa fa-close" aria-hidden="true"></i></span>
				</div>
			</div>
		</div>
	</li>
<?php
	}
}
else
	echo '<a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span>'.trans('messages.msg_norecordfound').'</a></li>';
?>	
