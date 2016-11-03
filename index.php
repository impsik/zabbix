<body>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</body>
<div class="container">
  <title>Monitoring</title>
<h1>Monitoring</h1>
<head>
  <script>
  function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "zabbixitem.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>
</head>
<table class="table table-hover">
  <thead>
    <tr>
      <th><u>Host</u></th>
      <th><u>Message</u></th>
      <th><u>Info</u></th>
    </tr>
</thead>

<?php
session_start();

$url = "http://IP_ADDRESS/zabbix/api_jsonrpc.php";
$content = json_encode(array("jsonrpc" => "2.0",
"method" => "trigger.get",
"params" => ["output" => ["triggerid","description","priority","lastChangeSince"],
"expandComment" => "1",
"expandData" => "1",
"expandDescription" => "1",
"filter" => ["value" => "1"],
"monitored" => "1",
"sortfield" => "priority",
"sortorder" => "DESC"],
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
$url = '<a href="http://IP_OR_DOMAIN/item.php?q=';
$data = json_decode($json_response,true);

foreach ($data['result'] as $key) {
  if ($key['priority'] == '5') {
echo "<tbody><tr class='danger'>";
echo "<td>".$key['hostname']."</td></b>";
echo "<td>".$key['description']."</td>";
echo "<td>".$url.$key['hostid']."\"target='_blank'>Link</a>"."</td>";
echo "</tr>";
  }
  else {
echo "<tr class='info'>";
echo "<td>".$key['hostname']."</td></b>";
echo "<td>".$key['description']."</td>";
echo "<td>".$url.$key['hostid']."\"target='_blank'>Link</a>"."</td>";
echo "</tr>";
}
}


?>
</tbody></table></div>
