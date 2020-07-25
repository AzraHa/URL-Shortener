<?php
require_once('database.php');

class Shortener 
{
    private $conn;

	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
    }
    public function urlToShort($url)
    {
        if(empty($url))
        {
            throw new Exception("No URL");
        }
        $shortCode = $this->urlExistInDatabase($url);
        if($shortCode == false)
        {
            $shortCode = $this->createShort($url);
        }
        return $shortCode;
    }
    protected function urlExistinDatabase($url)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT url_short_char FROM url_shortener WHERE url_text=:url_text LIMIT 1");
            $stmt ->execute(array("url_text"=>$url));
            $result = $stmt->fetch();
            return (empty($result)) ? false : $result["url_short_char"];
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }
    protected function createShort($url)
    {
        $shortCode = $this->randomChar();
        $id = $this->insertInDatabase($url,$shortCode);
        return $shortCode;
    }
    protected function randomChar()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    protected function insertInDAtabase($url,$code)
    {
        $stmt = $this->conn->prepare("INSERT INTO url_shortener(url_text,url_short_char) VALUES (:url_text,:url_short_char)");
        $stmt ->execute(array("url_text"=>$url,"url_short_char"=>$code));
        return $this->conn->lastInsertId();
    }
    public function shortCodetoUrl($code,$increment = true)
    {
        if(empty($code))
        {
            throw new Exception(("No short code was supplied"));
        }
        $urlRow = $this->getUrl($code);
        if(empty($urlRow))
        {
            throw new Exception ("Short code does not appear to exist");
        }
        if($increment == true)
        {
            $this->incrementCounter($urlRow["url_id"]);
        }
        return $urlRow["url_text"];
    }
        protected function getUrl($code)
        {
            $stmt = $this->conn->prepare("SELECT url_id,url_text FROM url_shortener WHERE url_short_char=:url_short_char LIMIT 1");
            $stmt->execute(array("url_short_char"=>$code));
            $result = $stmt->fetch();
            return(empty($result)) ? false : $result;

        }

        protected function incrementCounter($id)
        {
            $stmt = $this->conn->prepare("UPDATE url_shortener SET hits = hits + 1 WHERE url_id=:url_id");
            $stmt->execute(array("url_id"=>$id));

        }
}



?>