<?php
class CI_Page
{
	public $pageSign="page";	//page标签，用来控制url页。比如说xxx.php?Page=2中的Page
	public $pageBarNum=7;		//上一页下一页中间显示页数的数量，为了美观，请使用单数，双数也会被做单数处理
	public $dataNum;			//数据总数
	public $pageSize=10;		//分页大小
	public $params;				//传递参数
	public $pageName;			//跳转页面名称
	public $urlReWrite=false;	//伪静态

	public function getPage($dataNum='',$pageSize='',$param='',$pageName='')
	{
		if(!empty($dataNum))$this->dataNum=$dataNum;
		if(!empty($pageSize))$this->pageSize=$pageSize;
		if(!empty($param))$this->params=$param;
		if(!empty($pageName))$this->pageName=$pageName;

		/********** 定义分页显示样式在这里定义 ***********************************************/
		$str = '<ul class="az-page pagination">';
		$str .=$this->getStat();//显示统计信息
		$str .=$this->getPageFirst();//显示首页，不要可注释掉该行
		$str .= $this->getPagePrev();//显示上一页，不要可注释掉该行
		$str .= $this->getNumBar();//显示上一页和下一页中间的页码数，不要可注释掉该行
		$str .= $this->getPageNext();//显示下一页，不要可注释掉该行
		$str .= $this->getPageEnd();//显示尾页，不要可注释掉该行
		$str .= $this->getSelect();//显示下拉菜单跳转到指定页，不要可注释掉该行
		$str .= '</ul>';

		return $str;
	}

	//统计总数
	private function getStat()
	{
//		return "<span>$this->dataNum</span>";
		return "<li style='float: left;line-height: 35px;margin-right: 10px;'>共".$this->dataNum."条记录  第".$this->getPageCurrent()."/".ceil($this->dataNum/$this->pageSize)."页</li>";
	}

	//取得首页
	private function getPageFirst()
	{
		if($this->getPageCurrent()!=1)
			return '<li><a href="'.$this->pageName.'?'.$this->pageSign.'=1'.$this->params.'">首 页</a></li>';
	}

	//取得当前页
	private function getPageCurrent()
	{
		if(empty($_GET["$this->pageSign"]))
			$PageCurrent=1;
		else
			$PageCurrent=$_GET["$this->pageSign"];

		return $PageCurrent;
	}

	//取得总页数
	private function getPageCount()
	{
		return ceil($this->dataNum/$this->pageSize);
	}

	//取得上一页
	private function getPagePrev()
	{
		$pagePrev=$this->getPageCurrent()-1;
		if($this->getPageCurrent()!=1)
		{
			if($this->urlReWrite)
				$str = '<li><a href="'.$this->pageName.'_'.$pagePrev.$this->params.'.html" class="prev">上一页</a></li>';
			else
				$str = '<li><a href="'.$this->pageName.'?'.$this->pageSign.'='.$pagePrev.$this->params.'" class="prev">上一页</a></li>';

			return $str;
		}
	}

	//取得下一页
	private function getPageNext()
	{
		if($this->getPageCurrent()*$this->pageSize < $this->dataNum)
		{
			$pageNext=$this->getPageCurrent()+1;
			if($this->urlReWrite)
				$str = '<li><a href="'.$this->pageName.'_'.$pageNext.$this->params.'.html" class="next">下一页</a></li>';
			else
				$str = '<li><a href="'.$this->pageName.'?'.$this->pageSign.'='.$pageNext.$this->params.'" class="next">下一页</a></li>';

			return $str;
		}
	}

	//取得尾页
	private function getPageEnd()
	{
		if($this->getPageCurrent()*$this->pageSize < $this->dataNum)
		{
			$str = '<li><a href="'.$this->pageName.'?'.$this->pageSign.'='.$this->getPageCount().$this->params.'">尾 页</a></li>';

			return $str;
		}
	}

	//取得上下页之间的显示页数
	private function getNumBar()
	{
		$num=ceil($this->pageBarNum/2);
		$pageCount=$this->getPageCount();
		$pageCurrent=$this->getPageCurrent();

		if($pageCount <= $this->pageBarNum)
		{
			$pageBegin=1;
			$pageEnd=$pageCount;
		}else{
			if($pageCurrent > $num)
			{
				$pageBegin=$pageCurrent-$num+1;
				$pageEnd=0;
			}else{
				$pageBegin=1;
				$pageEnd=$num-$pageCurrent;
			}
			if($pageCount-$pageCurrent < $num)
			{
				$pageBegin=$pageBegin-($num-$pageCount+$pageCurrent-1);
				$pageEnd=$pageCount;
			}else{
				$pageEnd=$pageEnd+$pageCurrent+$num-1;
			}
		}
		$str = '';
		for($i=$pageBegin;$i<=$pageEnd;$i++)
		{
			if($i==$pageCurrent)
				$str .= "<li class='active'><a>{$i}</a></li>";
			else
			{
				if($this->urlReWrite)
					$str .= '<li><a href="'.$this->pageName.'_'.$i.$this->params.'.html">'.$i.'</a></li>';
				else
					$str .= '<li><a href="'.$this->pageName.'?'.$this->pageSign.'='.$i.$this->params.'">'.$i.'</a></li>';
			}
		}
		return $str;
	}

	//取得下拉列表
	private function getSelect()
	{
		$str = '<li style="float: left;line-height: 35px;margin-left: 10px;"> 跳转到<select onchange="location=this.options[this.selectedIndex].value" class="pageSelect">';
		for($i=1;$i<=$this->getPageCount();$i++)
		{
			if($this->getPageCurrent()==$i)
			{
				$str .= '<option selected="selected">第'.$i.'页</option>';
			}
			else
			{
				$str .= '<option value="'.$this->pageName.'?'.$this->pageSign.'='.$i.$this->params.'">第'.$i.'页</option>';
			}
		}
		$str .= '</select></li>';

		return $str;
	}
}
?>