<?php
$path_to_root=".";
if (!file_exists($path_to_root.'/config_db.php'))
  header("Location: ".$path_to_root."/install/index.php");

$page_security = 'SA_OPEN';
ini_set('xdebug.auto_trace',1);
include_once("includes/session.inc");

add_access_extensions();
$app = &$_SESSION["App"];
if (isset($_GET['application']))
  $app->selected_application = $_GET['application'];

if(isset($_SESSION["wa_current_user"]->loginname) && $_SESSION["wa_current_user"]->loginname != "" && !isset($_POST['sig_response'])){
  define('AKEY', "THISISMYSUPERSECRETCUSTOMERKEYDONOTSHARE");
  define('IKEY', "IKEY");
  define('SKEY', "SKEY");
  define('HOST', "api-xxxxxxxx.duosecurity.com");
  
  require_once 'duo_php/src/Web.php';
  $sig_request = Duo\Web::signRequest(IKEY, SKEY, AKEY, $_SESSION["wa_current_user"]->loginname);
?>
  <style>
  iframe#duo_iframe { margin-top: 100px; width: 450px;height: 400px; border-style: none; }
  </style>
  <script type="text/javascript" src="duo_php/js/Duo-Web-v2.js"></script>
  <center><iframe id="duo_iframe" data-host="<?php echo HOST; ?>" data-sig-request="<?php echo $sig_request; ?>"></iframe></center>
<?php
} else if(isset($_POST['sig_response'])){
  if (!isset($_SESSION["App"])) {
    $_SESSION["App"] = new front_accounting();
    $_SESSION["App"]->init();
  }
  
  $app->display();
}
