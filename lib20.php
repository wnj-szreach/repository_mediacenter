<?php

require_once($CFG->dirroot . '/repository/lib.php');

class repository_mediacenter_abs extends repository {

    protected $host       		= null;
    protected $url        		= null;

    protected $search_keyword  	= '';
    protected $search_type		= 'vod';

	protected function fetchResult($xml_object) {

		$list = array();

		if($this->search_type == 'vod') {// 点播解析
			if ($xml_object != null && $xml_object->MsgHead->ReturnCode == '1') {
				foreach($xml_object->MsgBody->Files->File as $f) {
					$list[] = array(
						'title'             =>  (string)($f->FileName),//另存为的名称
						'shorttitle'        =>  (string)($f->FileCName),//选择列表中的标题
						//'thumbnail'         =>  ($CFG->wwwroot).'/repository/mediacenter/source/video2.png',//图片
						'thumbnail'         =>  '../repository/mediacenter/source/video2.png',//图片
						//'thumbnail'         =>  ($this->host).((string)($f->PreviewUrl)),//图片
						'thumbnail_width'   =>  143,
						'thumbnail_height'  =>  98,
						//'thumbnail_width'   =>  320,
						//'thumbnail_height'  =>  240,
						'size'              =>  (int)($f->Size),//文件大小
						//'date'              =>  (int)($f->UTS),//日期  
						'author'            =>  (string)($f->Author),//作者
						//'icon'                =>  '',//找不到预览图的替代图标
						'source'            =>  $this->changeUrl2Moodle((string)($f->VodUrl))//文件源
					);  
				}
			}
		}else {// 直播解析
			if ($xml_object != null && $xml_object->MsgHead->ReturnCode == '1') {
				foreach($xml_object->MsgBody->RoomLives->RoomLive as $f) {
					$list[] = array(
						'title'             =>  (string)($f->ClassRoomName),//另存为的名称
						'shorttitle'        =>  (string)($f->ClassRoomName),//选择列表中的标题
						//'thumbnail'         =>  ($CFG->wwwroot).'/repository/mediacenter/source/video2.png',//图片
						'thumbnail'         =>  '../repository/mediacenter/source/video3.png',//图片
						//'thumbnail'         =>  ($this->host).((string)($f->PreviewUrl)),//图片
						'thumbnail_width'   =>  143,
						'thumbnail_height'  =>  98,
						//'thumbnail_width'   =>  320,
						//'thumbnail_height'  =>  240,
						'size'              =>  0,//文件大小
						//'date'              =>  (int)($f->UTS),//日期  
						'author'            =>  'system',//作者
						//'icon'                =>  '',//找不到预览图的替代图标
						'source'            =>  $this->changeUrl2Moodle2((string)($f->LiveUrls->LiveUrl[0]))//文件源
					);  
				}
			}
		}
		return $list;
	}

    public function type_config_form($mform) {
		parent::type_config_form($mform);
		repository_mediacenter::type_config_form_real($mform);
    }

    // 把获取到的地址改成通过moodle方法的代理地址
    private function changeUrl2Moodle($url) {
        if($url != null) {
			$arr = explode('?', $url);
			$arr2 = explode('repository', $_SERVER['PHP_SELF']);//娘的，处理可能的上下文，虽然PHP里面没有上下文的概念
            $url = 'http://'.$_SERVER['HTTP_HOST'].$arr2[0].'blocks/mediacenter_lbcontrol/proxy_vod.php?'.$arr[1];
        }
	}

    function changeUrl2Moodle2($url) {
        if($url != null) {
            $arr = explode('?', $url);
            $qstr = str_replace('&preview=1', '', $arr[1]);
            $url = '../'.'blocks/mediacenter_lbcontrol/proxy_live.php?'.$qstr;
        }
        return $url;
    }

	public function print_search() {
		$html = '';
		$html .= '<table><tr>';
		$html .= '<td><input style="width:160px;" type="text" name="s" value="" /></td>';
		//$html .= '<td><input style="width:30px;" type="radio" name="searchtype" value="vod" /></td><td style="width:50px;">'.get_string('vod', 'repository_mediacenter').'</td>';
		//$html .= '<td><input style="width:30px;" type="radio" name="searchtype" value="live" /></td><td style="width:50px;">'.get_string('live', 'repository_mediacenter').'</td>';
		$html .= '<td><input style="width:30px;" type="radio" name="searchtype" value="vod" /></td><td>VOD</td>';
		$html .= '<td><input style="width:30px;" type="radio" name="searchtype" value="live" /></td><td>LIVE</td>';
// 20版本自带提交按钮
		
		$html .= '</tr></table>';
		
		/*$html .= '<script type="text/javascript">';
		$html .= 	'function radio_click(obj){';
		$html .=		'alert(obj.value);';
		$html .= 	'}';
		$html .= '</script>';
*/
	 
		return $html;
	}
}
 
