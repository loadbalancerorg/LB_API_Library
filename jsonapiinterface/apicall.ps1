Param(  $loadbalancer, $json, $username, $password,  $apikey )
echo "Connecting to ${loadbalancer}"
$pair = "$username:$password"
$bytes = [System.Text.Encoding]::ASCII.GetBytes($pair)
$base64 = [System.Convert]::ToBase64String($bytes)
$basicAuth = "Basic $base64"
$apikeybytes = [System.Text.Encoding]::ASCII.GetBytes($apikey)
$apikey = [System.Convert]::ToBase64String($apikeybytes)
$jsonfile = Get-Content $json  -Raw
$headers = @{}
$headers.Add( "Authorization", $basicAuth )
$headers.Add( "X_LB_APIKEY", $apikey )
$uri="https://${ip}:9443/api/v2/"
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
[System.Net.ServicePointManager]::CertificatePolicy = New-Object
TrustAllCertsPolicy
$response = Invoke-WebRequest -Uri $uri -Method:Post -Body $jsonfile -ContentType "application/json" -Headers $headers

