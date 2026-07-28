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

    public function logActivity($userID, $activityType, $blogID = null, $pageURL = '')
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $creationDate = date("Y-m-d H:i:s");
            
            $sql = "INSERT INTO user_activity_log (UserID, ActivityType, BlogID, PageURL, IPAddress, UserAgent, CreationDate)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssss", $userID, $activityType, $blogID, $pageURL, $ip, $ua, $creationDate);
                $stmt->execute();
            }
        } catch (Exception $e) {
            $this->Errorlog("Failed to log activity: " . $e->getMessage());
        }
    }
}






?>