<?php 

if (!class_exists('counter'))
{

	/*

	-------------------------------------------
	Classe permettant d'afficher le nombre de
	connectés d'un site sans base de données.
	------------------------------------------

	*/


	class counter
	{
	var $dir;

	var $file;

	var $idle;

	var $userIp;

	var $cache_file;

	var $cache_time;

	var $cache_filename;

	/*
	$count : Gardera en mémoire le nombre de connectés.


	*/


	var $count = false;

	/*

	Constructeur


	*/


	function counter($dir = 'howmany/', $idle = 300, $cache_time = 60, $cache_filename = 'cache.txt')
	{
	$dir = substr($dir, -1) == '/' ? $dir : $dir . '/';

	if (!is_dir($dir))
	{
	mkdir($dir);
	}

	$this->dir = $dir;

	$this->idle = $idle;

	$this->userIp = $this->getIp();

	$this->file = $this->dir . md5($this->userIp);

	$this->cache_filename = $cache_filename;

	$this->cache_file = $this->dir . $cache_filename;

	$this->cache_time = $cache_time;
	}

	/*

	update : Met à jour le fichier de l'utilisateur courant


	*/


	function update()
	{
	if (!@file_exists($this->file) || !@touch($this->file))
	{
	fopen($this->file, 'w');
	}
	}

	/*

	garbage : Nettoie le dossier - Suppréssion des fichiers obsolètes


	*/


	function garbage()
	{
	$timeCacheVerif = time() - $this->cache_time;

	if ($h = opendir($this->dir))
	{
	while (false !== ($f = readdir($h)))
	{
	if ($f != '.' && $f != '..' && $f != $this->cache_filename)
	{
	$cfp = $this->dir . '/' . $f;
		
	if (@filemtime($cfp) < $timeVerif)
	{
	@unlink($cfp);
	}
	}
	}
	}
	}

	/*

	view : Affiche le nombre de connectés au site


	*/


	function view($text = true)
	{
	if ($this->count !== false)
	{
	$nb = $this->count;
	}
	else
	{
	$nb = $this->count();
	}

	return $nb . ($text ? ' connecté' . ($nb>1?'s':'') : '');
	}

	/*

	getIp : Renvoie l'adresse IP de l'utilisateur


	*/


	function getIp()
	{
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif (isset($_SERVER['HTTP_CLIENT_IP']))
	{
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else
	{
	$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
	}

	/*

	count : Retourne le nombre de connectés


	*/


	function count()
	{
	//clearstatcache();

	$ok = false;

	$timeCacheVerif = time() - $this->cache_time;

	if (file_exists($this->cache_file) && @filemtime($this->cache_file) > $timeCacheVerif)
	{
	$content = $this->read($this->cache_file);
		
	$key = substr($content, 0, 32);
		
	$i = substr($content, 32);
		
	if ($key == md5($i))
	{
	$ok = true;
	}
	}

	if (!$ok)
	{
	if (!is_dir($this->dir)) return false;

	$timeVerif = time() - $this->idle;
		
	$i = 0;

	if ($h = opendir($this->dir))
	{
	while (false !== ($f = readdir($h)))
	{
	if ($f != '.' && $f != '..' && $f != $this->cache_filename)
	{
	$cfp = $this->dir . '/' . $f;

	if (@filemtime($cfp) > $timeVerif)
	{
	$i++;
	}
	else
	{
	@unlink($cfp);
	}
	}
	}
	}
		
	$this->write($this->cache_file, md5($i) . $i, 'w');
	}

	$this->count = $i;

	return $this->count;
	}

	/*

	write : Ecrit dans un fichier


	*/


	function write($file, $content, $mode = 'a')
	{
	$fp = fopen($file, $mode);

	if ($fp)
	{
	@flock($fp, LOCK_EX);
		
	@fwrite($fp, $content, strlen($content));

	@flock($fp, LOCK_UN);
		
	@fclose($fp);
		
	return true;
	}

	return false;
	}

	/*

	read : Lit dans un fichier


	*/


	function read($file)
	{
	if (!@file_exists($file)) return false;

	$fp = @fopen($file, 'r');

	if ($fp)
	{
	@flock($fp, LOCK_SH);
		
	$content = @fread($fp, @filesize($file));
		
	@flock($fp, LOCK_UN);
		
	@fclose($fp);

	return $content;
	}

	return false;
	}

	}

	}

?>