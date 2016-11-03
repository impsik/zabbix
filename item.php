<body>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</body>
<div class="container">
  <title>Monitoring</title>
<h1>Monitoring</h1>
<table class="table table-hover">


<?php
session_start();
$q = $_REQUEST["q"];
$url = "http://IP_ADDRESS/zabbix/api_jsonrpc.php";
$content = json_encode(array( "jsonrpc"=> "2.0",
"method"=> "host.get",
"params"=> ["selectInventory"=> true,"selectItems"=> [
"selectInterfaces"=> [
"interfaceid",
"ip"],
"name",
"key_",
"lastvalue",
"itemid",
],
"output"=> "extend",
"hostids"=> "$q",
"expandDescription"=> 1,
"expandData"=> 1
    ],
"auth" => "AUTH_KEY",
"id" => "1"));

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
$url = '<a href="http://IP_OR_DOMAIN/zabbix/latest.php?hostid=';
$data = json_decode($json_response,true);

foreach ($data['result'] as $key) {
echo "<td><b>".$key['name']."</td></b>";
  foreach ($key['items'] as $items){
echo "<tbody><tr class='danger'>";
echo "<td>".$items['name']."</td>";
$sumgb = round($items['lastvalue'] / 1073741824, 2);
$sumpercentage = round($items['lastvalue'], 2);
$cpuusage = round($items['lastvalue']/ 1000000000, 1);
$uptime = round($items['lastvalue'] / 86400,2 );
if (preg_match("/.size/",$items['key_']) && !preg_match("/percentage/",$items['name'])){
  echo "<td>".$sumgb."GB"."</td></tr>";
          echo "</tr>";
}
elseif (preg_match("/percentage/",$items['name'])){
    echo "<td>".$sumpercentage."% </td>";
    }
elseif (preg_match("/cpu.usage/",$items['key_'])){
    echo "<td>".$cpuusage."</td>";
    }
elseif (preg_match("/uptime/",$items['key_'])){
    echo "<td>".$uptime." päeva</td>";
    }
elseif (preg_match("/storage/",$items['key_'])){
    echo "<td>".$sumgb."GB"."</td>";
    }

elseif (preg_match("/ssl/",$items['key_'])){
    echo "<td>".substr($items['lastvalue'],0,-5)." päeva</td>";
    }
      else {
                echo "<td>".$items['lastvalue']."</td>";
      }

  }
}
echo "</tbody></table></div>";
?>

