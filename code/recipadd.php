#!/usr/bin/php
<?php
if (count($argv) < 2)
{
	die("Usage: ".$agrv[0]." INFILE [GID]");
}
$gid = 0;
if (count($argv) > 2)
{
	if (intval($argv[2]) > 0)
	{
		$gid = $argv[2];
	}
	else
	{
		die("Positive integer GID expected");
	}
}

$infile = fopen($argv[1], 'r');
$db = new PDO('sqlite:'.dirname(__FILE__).'/recipients.db');
if ($infile) 
{
	while (($buffer = fgets($infile, 512)) !== false) 
	{
		$email = sqlite_escape_string(trim($buffer));
		echo "Found email: $email ...";
		$msg = "";
		if ($db->exec('insert into recipients values (NULL,'.$gid.',"'.$email.'",0,0,0,0,0)'))
		{
			echo " Inserted.\n";
		}
		else
		{
			$error_info = $db->errorInfo();
			$msg = $error_info[2];
			echo " Error:\n$msg\n";
		}
	}
}
else 
{
	fclose($infile);
	die("Cannot open the file with addresses list: $infilename");
}
fclose($infile);

	

