<?php
class ReceiveResult
{
	//code
	private $errcode;
	//message
	private $errmsg;
	
	private $dataStr;
	
	private $responseContent;
	
	//传入json串
	public function setResultStr($rs, $errcode)
	{
	    $status = explode(" ",$rs["header"][0]);
	    if($errcode ==200 && $status[1] == 200)
		{
		    //var_dump( $rs["header"]);
			$this->errcode = $errcode;
			$limit = explode(":", $rs["header"][5]);
			$remaining = explode(":", $rs["header"][6], 2);
			$reset = explode(":", $rs["header"][7], 2);
			$this->responseContent = array("X-Rate-Limit-Limit"=>$limit[1],
										   "X-Rate-Limit-Remaining"=>$remaining[1],
										   "X-Rate-Limit-Reset"=>$reset[1]);
		    //var_dump( $this->responseContent);
			$this->dataStr = $rs["body"];
		}
		else
		{
			$this->code = $status[1];
			switch ($status[1])
			{
			case 400:
			    $this->message = "Your request params is invalid. Please check them according to docs.";
			  break;  
			case 401:
			    $this->message = "Authentication failed! Please check authentication params according to docs.";
			  break;
			case 403:
			    $this->message = "Request is forbidden! Maybe your appkey is listed in blacklist?";
			  break;
			case 429:
			    $this->message = "Too many requests! Please review your appkey's request quota.";
			  break;
			default:
			    $this->message = "error new add.";	
			}
		}
	    //echo $rs.'<br/>';
		//echo $dataArry;
	}
	
	public function isOK()
	{
	    return $this->errcode == 200;
	}
	
	public function getResultStr()
	{
	    return $this->dataStr;
	}
	
	public function getResponseContent()
	{
	    return $this->responseContent;
	}
}