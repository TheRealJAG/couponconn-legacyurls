<?php
/**
 * ExistingContent Class
 *
 * This class is intended to run before a HTTP request is made to the Laravel framework.
 * Once executed, the class will determine if there is a existing post with a matching URL string.
 * If a post is found the class will then 301 redirect the client to the correct content. 
 *
 * @author Jorge Gonzalez
 * @copyright  2015 
 * @version    1.0
 */ 
  
class ExistingContent { 
   
    public $permalink;
    public $redirect;
    public $urlpath;
  
    private $hostname;          // MySQL Hostname
    private $username;          // MySQL Username
    private $password;          // MySQL Password
    private $database;          // MySQL Database
    private $databaseLink;      // Database Connection Link
  
    /**
    * ExistingContent::__construct()
    * 
    * @param mixed $url
    * @return
    */
       function __construct($url) {
            $this->url = $url;
            $this->subdir = '';
            $this->hostname = 'localhost';
            $this->database = '';
            $this->username = '';
            $this->password = '';
            $this->GetPermalink();
            $this->Connect();
            $this->Query();
            $this->Redirect();
        }
    
    /**
    * ExistingContent::__destruct()
    * Close the mysqli connection on __destruct
    * 
    * @return
    */
    	 function __destruct(){
    		$this->closeConnection();
    	}
    
    /**
    * ExistingContent::Connect()
    * Coonect to the database
    * 
    * @return
    */
       
      public function Connect() {    
        $this->databaseLink = new mysqli($this->hostname, $this->username, $this->password, $this->database);        
    		if(!$this->databaseLink){
       		    $this->lastError = 'Could not connect to server: ' . mysql_error($this->databaseLink);
    			return false;
    		}		
      }  
   
    /**
    * ExistingContent::GetPermalink()
    * Get the permalink from the http request
    * 
    * @return
    */
      public function GetPermalink() {    
        $url_parts = parse_url($this->url);
        $this->urlpath = $url_parts['path'];
        $this->permalink = substr($this->urlpath, 1, -1);  
      } 
       
   
    /**
    * ExistingContent::Query()
    * Query the wp_posts table for a matching permalink
    * 
    * @return
    */
      public function Query() {
        $result = $this->databaseLink->query("SELECT post_name FROM wp_posts where post_name = '".$this->permalink."' LIMIT 1");     
        $row = $result->fetch_array(MYSQLI_NUM); 
        $result->close();  
        if ($row[0]) $this->redirect = true;
         else echo 'no match. continue on. remove this line in production';
      } 
  
    /**
    * ExistingContent::Redirect()
    * If a matching permalink is found, redirect the client to the new URL
    * 
    * @return
    */
      public function Redirect() {
        if ($this->redirect==true) {
            echo 'redirect client to '.$this->subdir.$this->urlpath;
            #Header( "HTTP/1.1 301 Moved Permanently" ); 
            #Header( "Location: ".$this->subdir.$this->urlpath."" ); 
            #exit;
        } 
      }   
    
    /**
    * ExistingContent::closeConnection()
    * Close the mysqli connection
    * 
    * @return
    */
      public function closeConnection(){
            if($this->databaseLink){
                mysqli_close($this->databaseLink);
            }
        }      
}
 
$myShip = new ExistingContent($_GET['url']);
$time2 = microtime(true);
?>
