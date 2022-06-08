<?php

class Dump
{
	private $count = 0;
	public $html;

	public function __construct($args)
	{
		$this->html .= $this->header();
		$this->html .= $this->d($args);
		$this->html .= $this->footer();
	}

	public function d($variables)
	{
        if(is_string($variables)){
            $this->html .= "str :<span class='str'>$variables</span>";
            $this->html .= $this->footer();
			echo $this->html; exit;
        }
		if(is_integer($variables))
		{
			$this->html .= "int: <span class='int'>$variables</span>";
            $this->html .= $this->footer();
			echo $this->html; exit;
		}
		if(is_bool($variables))
		{
			$this->html .= "<span class='character'>".print_r($variables)."</span>";
            $this->html .= $this->footer();
			echo $this->html; exit;
		}

		foreach ($variables as $key => $value){
			$isObj = is_object($value);
			$isArray = is_array($value);
			$isKeyString = is_string($key);
			$isKeyInt = is_integer($key);
			$isValueString = is_string($value);
			$isValueInt = is_integer($value);
			if ($isObj || $isArray) 
			{
				$total =  $isArray ? count($value) : count(get_object_vars($value));
				$this->html .= "<span class='block-item'>";
				$this->html .= "<span class=' ".($isKeyString ? "str":"").($isKeyInt ? "int":"")."'>$key</span>";
				$this->html .= "<span class='character'> => </span> ".($isArray ? "Array":"Object" ).": <span class='int'>$total</span>";

				$this->html .= "<span class='character bracket-open'>".($isArray ? " [":" {")."</span>";
				$this->html .= "<span class='arrow $this->count ".($this->count <= 1 ? 'arrow-down' : '' )."'></span>";
				$this->html .= "<span class='block-belong' $this->count style='".($this->count > 1 ? 'display:none;' : '' )."'>";
				$this->d($value);
				$this->html .= "</span>";
				$this->html .= "<span class='character'>".($isArray ? "]":"}" )."</span>";
				$this->html .= "</span>";
			}
			else
			{
				$this->html .= "<span class='block-item'>
						<span class='key ".($isKeyString ? "str":"").($isKeyInt ? "int":"")."'>$key</span>
						<span class='character'> => </span>
						<span class='value ".($isValueString ? "str":"").($isValueInt ? "int":"")."'>$value</span>
						</span>";
			}	
			$this->count += 1;
			if ($this->count > 500) {
				$this->html .= $this->footer();
				echo $this->html;
				exit;
			}
		}
	}

	public function header()
	{
		return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Document</title><style>*{padding-left:0px;font:13px/18px monospace}
		.parent{position:relative;}.block-item{display:block;position:relative;left:15px;border-left:1px dotted gray;padding-left: 15px;}.str{color:#0B7500;}.int{color:#1A01CC;}.str::after{content:"\""}.str::before{content:"\""}.character{color:;}.arrow{width:20px;height:18px;position:absolute;left:-2px;top:1px;z-index:5;background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD1JREFUeNpiYGBgOADE%2F3Hgw0DM4IRHgSsDFOzFInmMAQnY49ONzZRjDFiADT7dMLALiE8y4AGW6LoBAgwAuIkf%2F%2FB7O9sAAAAASUVORK5CYII%3D");background-repeat:no-repeat;background-position:center center;display:block;opacity:0.5;-webkit-transform:rotate(-90deg);}.arrow-down{-webkit-transform:rotate(0deg);} </style></head><body>';	
	}

	public function footer()
	{
		return '<footer><script type="text/javascript">var spanElement = document.getElementsByClassName("arrow");for (var i = 0; i< spanElement.length ; i ++) {spanElement[i].addEventListener("click", function(){var key = 0,_self = this,nextElment = _self.nextElementSibling,displaySibling = nextElment.style.display;if (displaySibling == "none") {nextElment.style.display = "block";this.classList.add("arrow-down");}else {nextElment.style.display = "none";this.classList.remove("arrow-down");}})};</script></footer></body></html>';	
	}
}

function dd($args)
{
	$Dump = new Dump($args);
	echo $Dump->html;die;
}