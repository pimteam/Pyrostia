<?php
/**
 * Celeroo Frame
 *
 * An open source rapid development framework for PHP 4.3.2 or newer
 *
 * @package		Celeroo Frame
 * @author		Celeroo, www.celeroo.com
 * @copyright	Copyright (c) 2008, Celeroo.
 * @license		
 * @link		
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * PHP Systems Email Class
 *
 * This class contains functions that enable benchmarking code performance
 *
 * @package		PHP systems
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Bobby Handzhiev
 * @link		
 */

class Email
{
	var $headers = array();
	
	/**
	 * Constructor
	 *
	 * Sets default values in the headers array
	 *
	 * @access   public	
	 */
	function __construct()
	{
		$this->headers=array(
		"Return-path:"=>"",
		"Envelope-to:"=>"",
		"From:"=>"",
		"MIME-Version:"=>"1.0",
		"Content-Type:"=>"multipart/mixed;\n\tboundary=\"----=_NextPart_000_0001_00000001.00000001\"",
		"X-priority:"=>"3",
		"X-MSMail-Priority:"=>"Normal",
		"X-Mailer:"=>"clsImap Mail Handler 2.0"
		);
	}
	
	/**
	 * Sends email
	 *
	 * @access	public
	 * @param	string	sender email address
	 * @param	string	receiver email address
	 * @param	string	email subject
	 * @param	string	email message
	 * @param	string	sender name, optional
	 * @return	string  boolean
	 */	
	public function send($from,$to,$subject,$message,$from_name='')
	{
		$this->to=$to;
		$this->from_addr=$from;
		$this->from_name=$from_name;
		$this->subject=$subject;
		
	   	$messge="This is a multi-part message in MIME format.\n";
		
		$message.="\n------=_NextPart_000_0001_00000001.00000001\nContent-type: multipart/alternative;\n\tboundary=\"----=_NextPart_000_0001_00000001.00000011\"";
		$message.="\n\n\n------=_NextPart_000_0001_00000001.00000011\nContent-type: text/plain;\n\tcharset=\"iso-8859-1\"\nContent-Transfer-Encoding: quoted-printable\n\n".$this->body_parts["text/plain"];
		$message.="\n------=_NextPart_000_0001_00000001.00000011\nContent-type: text/html;\n\tcharset=\"iso-8859-1\"\nContent-Transfer-Encoding: quoted-printable\n\n".$this->body_parts["text/html"];
		$message.="\n\n------=_NextPart_000_0001_00000001.00000011--\n";
		for ($i=0; $idx<count($this->parts); $i++) {
			$message.="\n------=_NextPart_000_0001_00000001.00000001\n";
			$message.=$this->parts[$i];
		}
		$message.="\n------=_NextPart_000_0001_00000001.00000001--\n";
	
		if ($this->headers["Return-Path:"]=="")
		{
			 $this->headers["Return-Path:"]="<".$this->from_addr.">";
		}
		
		if ($this->headers["Envelope-To:"]=="") 
		{
			$this->headers["Envelope-To:"]=$this->to;
		}
		
		if ($this->from_name!="" && $this->from_addr!="")
		{
			 $this->headers["From:"]="\"".$this->from_name."\" <".$this->from_addr.">";
		}
		elseif ($this->from_addr!="") 
		{
			$this->headers["From:"]=$this->from_addr;
		}
		else return "No sender specified";
	
		$headers="";
		reset($this->headers);
		while (list($k,$v)=each($this->headers)) 
		{
			$headers.="$k $v\n";	
		}
		mail($this->to,$this->subject,$message,chop($headers));
	}
	
	/**
	 * Attaches a file
	 *
	 * @access	public
	 * @param	string	name and path to the file	 	 
	 */	
	public function attach($fullname)
	{
		$fname=basename($fullname);
		$part="Content-Type: $ftype;\n\tname=\"$fname\"\n";
		$part.="Content-Transfer-Encoding: base64\n";
		if (!empty($fdispos)) $part.="Content-Disposition: $fdispos;\n\tfilename=\"$fname\"\n";
		if (!empty($fdescrip)) $part.="Content-Description: $fdescrip\n";
		if (!empty($fid)) $part.="Content-Id: <$fid>\n";
		$part.="\n";
	
		$data=base64_encode(fread(fopen($fullname,"r"),filesize($fullname)));
		$data1=$data;
		while (strlen($data1)>76) 
		{
			$data.=substr($data,0,76)."\n";
			$data=substr($data,76);
		}
		$part.=$theData."\n";
		$this->parts[]=$part;
	}
}

// End Email class
?>