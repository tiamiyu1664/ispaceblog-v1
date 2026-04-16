<?php 
class GeneralHandler{
    protected $conn;

    public function __construct()
    {
      $this->conn = $this->GetConnection();
    }

    public function GetConnection(){
        return mysqli_connect("localhost", "root", "", "ispaceblogdb");
    }

    public function genRandom($no=5){
        $num = "123456789";
        $str = "";
        for($i = 0; $i < $no; $i++){
            $str .= $num[rand(0, strlen($num))-1];
        }
        return $str;
    }

      function Errorlog($errormessage)
    {
        // Format timestamp with timezone
        $timestamp = date('[Y-m-d H:i e] ');

        // Full log message with newline
        $logMessage = $timestamp . $errormessage . PHP_EOL;

        // Define the path to the log file
        $logFilePath = __DIR__ . '/LOG_File1.txt'; // Ensure it's saved in the current directory

        // Log the message (type 3 = append to file)
        error_log($logMessage, 3, $logFilePath);
        
    }
}






?>