<?php
// Last update: 2020.06.29
if(!isset($ab_version)) die('access denied');

if ($ab_config['re_check'] == 1) {
echo '<script src="https://www.google.com/recaptcha/api.js?render='.$ab_config['recaptcha_key'].'"></script>';
}
?>
  <script>
if (window.location.hostname !== window.atob("<?php echo base64_encode($ab_config['host']); ?>")) {
window.location = window.atob("<?php echo base64_encode('http://'.$ab_config['host'].$ab_config['uri']); ?>");
throw "stop";
}
</script>
<script>
setTimeout(Button, <?php echo $ab_config['timer']; ?>000);
//var action = '<?php echo $ab_config['country']; ?>';
var action = '<?php echo preg_replace("/[^0-9a-z]/","", $ab_config['host']); ?>';
var h1 = '<?php echo md5($ab_config['email'].$ab_config['pass'].$ab_config['host'].$ab_config['useragent'].$ab_config['accept_lang'].$ab_config['ip_short'].$ab_config['re_check'].$ab_config['ho_check']); ?>';
var h2 = '<?php echo md5('Antibot:'.$ab_config['email']); ?>';
var ip = '<?php echo $ab_config['ip_short']; ?>';
var via = '<?php echo $ab_config['http_via']; ?>';
var v = '<?php echo $ab_version; ?>';
var re = '<?php echo $ab_config['re_check']; ?>';
var ho = '<?php echo $ab_config['ho_check']; ?>';
var cid = '<?php echo $ab_config['cid']; ?>';
var ptr = '<?php echo $ab_config['ptr']; ?>';
var width = screen.width;
var height = screen.height;
var cwidth = document.documentElement.clientWidth;
var cheight = document.documentElement.clientHeight;
var colordepth = screen.colorDepth;
var pixeldepth = screen.pixelDepth;
var phpreferrer = '<?php echo preg_replace("/[^0-9a-z-.:]/","", parse_url($ab_config['refhost'], PHP_URL_HOST)); ?>';
var referrer = document.referrer;
if (referrer != '') {var referrer = document.referrer.split('/')[2].split(':')[0];}

<?php if ($ab_config['re_check'] == 1) { ?>
grecaptcha.ready(function() {
document.getElementById("btn").innerHTML = '☑☐☐'; // receiving token
grecaptcha.execute('<?php echo $ab_config['recaptcha_key']; ?>', {action: action}).then(function(token) {
document.getElementById("btn").innerHTML = '☑☑☐'; // token received
var data = 'action='+action+'&token='+token+'&h1='+h1+'&h2='+h2+'&ip='+ip+'&via='+via+'&v='+v+'&re='+re+'&ho='+ho+'&cid='+cid+'&ptr='+ptr+'&w='+width+'&h='+height+'&cw='+cwidth+'&ch='+cheight+'&co='+colordepth+'&pi='+pixeldepth+'&ref='+referrer;
CloudTest(window.atob('<?php echo base64_encode($ab_config['check_url']); ?>'), 4000, data, 0);
});
});      
<?php } else { ?>
function nore() {
document.getElementById("btn").innerHTML = '☑☑☐';
var token = '0';
var data = 'action='+action+'&token='+token+'&h1='+h1+'&h2='+h2+'&ip='+ip+'&via='+via+'&v='+v+'&re='+re+'&ho='+ho+'&cid='+cid+'&ptr='+ptr+'&w='+width+'&h='+height+'&cw='+cwidth+'&ch='+cheight+'&co='+colordepth+'&pi='+pixeldepth+'&ref='+referrer;
CloudTest(window.atob('<?php echo base64_encode($ab_config['check_url']); ?>'), 4000, data, 0);
}
setTimeout(nore, <?php echo $ab_config['timer']; ?>000);
<?php } ?>

function Button() {
<?php if ($ab_config['input_button'] != 1) { ?>
document.getElementById("btn").innerHTML = window.atob("<?php echo base64_encode('<form action="" method="post"><input name="time" type="hidden" value="'.$ab_config['time'].'"><input name="antibot" type="hidden" value="'.md5($ab_config['salt'].$ab_config['time'].$ab_config['ip'].$ab_config['useragent']).'"><input name="cid" type="hidden" value="'.$ab_config['cid'].'"><input style="cursor: pointer;" class="btn btn-success" type="submit" name="submit" value="Click to continue"></form>'); ?>");
document.getElementsByName('submit')[0].value = "<?php echo abTranslate('Click to continue'); ?>";	
<?php } ?>
}

function CloudTest(s, t, d, b){
var cloud = new XMLHttpRequest();
cloud.open("POST", s, true)
cloud.setRequestHeader('Content-type', 'application/x-www-form-urlencoded;');
cloud.timeout = t; // time in milliseconds

cloud.onload = function () {
if(cloud.status == 200) {
  document.getElementById("btn").innerHTML = '☑☑☑';
  console.log('good: '+cloud.status);
var obj = JSON.parse(this.responseText);
if (typeof(obj.error) == "string") {
document.getElementById("error").innerHTML = obj.error;
}
if (typeof(obj.cookie) == "string") {
document.getElementById("btn").innerHTML = "<?php echo abTranslate('Loading page, please wait...'); ?>";
var d = new Date();
d.setTime(d.getTime() + (7*24*60*60*1000));
var expires = "expires="+ d.toUTCString();
document.cookie = "<?php echo 'antibot_'.md5($ab_config['salt'].$ab_config['host'].$ab_config['ip_short']); ?>="+obj.cookie+"; " + expires + "; path=/;";
document.cookie = "lastcid="+obj.cid+"; " + expires + "; path=/;";
location.reload(true);
} else {
Button();
console.log('bad bot');
}
} else {
document.getElementById("btn").innerHTML = '☑☑☒';
  console.log('other error');
  if (b == 1) {Button();} else {CloudTest(window.atob('<?php echo base64_encode($ab_config['check_url2']); ?>'), 4000, d, 1);}
}
};
cloud.onerror = function(){
	document.getElementById("btn").innerHTML = '☑☑☒';
	console.log("error: "+cloud.status);
	if (b == 1) {Button();} else {CloudTest(window.atob('<?php echo base64_encode($ab_config['check_url2']); ?>'), 4000, d, 1);}
}
cloud.ontimeout = function () {
  // timeout
document.getElementById("btn").innerHTML = '☑☑☒';
  console.log('timeout');
  if (b == 1) {Button();} else {CloudTest(window.atob('<?php echo base64_encode($ab_config['check_url2']); ?>'), 4000, d, 1);}
};
cloud.send(d);
}
</script>
