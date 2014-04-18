<?php

require_once($CFG->dirroot . '/repository/lib.php');

class repository_mediacenter_abs extends repository {

    protected $host       		= null;
    protected $url        		= null;

    protected $search_keyword  	= '';

	protected function fetchResult($xml_object) {

		$list = array();

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
					'source'            =>  (string)($f->VodUrl)//文件源
				);  
			}
		}
		return $list;
	}

    public function type_config_form($mform) {
		parent::type_config_form($mform);
		repository_mediacenter::type_config_form_real($mform);
    }
}
 
