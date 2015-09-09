<?PHP
set_time_limit(10);
print "\n-----TESTING REST POST-----\n";
test_post();
function test_post() {
   $data = array("name" => "bolt","email"=>"jasonzhangxian@163.com");
   $data_string = json_encode($data);
   $ch = curl_init('http://localhost/api/Example/users/1');
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	   'X-HTTP-Method-Override: DELETE',
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string))
   );
   $result = curl_exec($ch);
   $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   $contenttype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
   print "Status: $httpcode" . "\n";
   print "Content-Type: $contenttype" . "\n";
   print "\n" . $result . "\n";
}