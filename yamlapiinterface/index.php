<?php
/*

(c) 2020 Loadbalancer.org - YAML API INTERFACE
IMPLIMENTATION Written by Andruw Smalley

Loadbalancer lbcli YAML lbcli poster /api/v2/yaml
This will just work with any version from v7.6.3 - v8.4.2 
Prior to v8.3.8 JSON responses likely not converted to YAML

Yes it works with ANY loadbalancer.org appliance which 
works with lbcli

Note you will know what you can do in each version of lbcli 
for the API interface to work.


Example YAML API Usage place

PUT /var/www/html/api/v2/yaml/index.php 
OR anywhere under /var/www/html/ if you wish.

lbcli:
- action: hostname
function: set
hostname: mole
domain: master.lb.zerodns.co.uk

// YAML IMPLIMENTATION by Andruw Smalley

Call with either api/v2 powershell, php or curl 
interfaces, simply change the file format. 

*/

date_default_timezone_set('Europe/London');

require_once("/etc/loadbalancer.org/api-credentials");
if(!$_SERVER["DOCUMENT_ROOT"]) {
    die("error:\n-  call: me\n   from: network\n");
}
putenv("SERVER_ADDR=" . $_SERVER["SERVER_ADDR"]);

if (!isset($username, $password, $apikey)) {
    exit;
} else if (!isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="API"');
    header('HTTP/1.0 401 Unauthorized');
    echo "lbapi:\n- noway: noyaml\n";
    exit;
} else if ($username == $_SERVER['PHP_AUTH_USER'] && $password == $_SERVER['PHP_AUTH_PW']) {
    if ($_SERVER["HTTP_X_LB_APIKEY"]) {
        if (trim(base64_decode($_SERVER["HTTP_X_LB_APIKEY"])) == $apikey) {

            $fp = fopen('php://input', 'r') or die("  input: failed\n");
            $act = 0;
            while (!feof($fp)) {
                list($key, $value) = explode(":", trim(str_replace("\n", "", fgets($fp))));
                $key = str_replace("- ", "", $key);
                if ($key == "lbcli") {
                    $actok = true;
                    echo "Continuing:\n";
                    continue;
                }
                if ($actok !== true) {
                    echo "noact:\n";
                    return false;
                }
                if ($key == "action") {
                    if (!isset($act)) {
                        $act = -1;
                    }
                    $act++;
                    $lbcli[$act] = "/usr/local/sbin/lbcli --action " . trim($value) . " --method api";
                } else {
                    if (isset($value)) {
                        $lbcli[$act] .= " --" . $key . " " . trim($value);
                    }        
                }          
            }
            fclose($fp);
            $return = yamlout($lbcli);     
        } else {
            header('WWW-Authenticate: Basic realm="YAMLAPI"');
            header('HTTP/1.0 401 Unauthorized');
            echo "lbapi:\"- json: invalid\n";
            die();
        }
    } else {
        header('WWW-Authenticate: Basic realm="API"');
        header('HTTP/1.0 401 Unauthorized');
        echo "lbapi:\n- apikey: failed\n";
        die();
    }
}

function yamlout($lbcli) {
    echo "lbcli:\n";
    foreach ($lbcli as $call) {
        $output    = "[" . shell_exec($cmd . $call) . "]";
        $responses = (json_decode($output, true));
        foreach ($responses as $iteration => $response) {
            $yam[] = $response['itteration']['0']['lbcli']['0'];
        }
    }
    $count   = count($yam);
    $counter = 0;
    while ($counter < $count) {
        foreach ($yam[$counter] as $key => $value) {
            if ($key == "action" || $key == "core") {
                echo "- " . trim($key) . ": " . trim($value) . "\n";
            } else {
                if (!is_array($value)) {
                    echo "  " . trim($key) . ": " . trim($value) . "\n";
                } else {
                    echo "  " . trim($key) . ":\n";
                    if ($value['0']) {
                        $value = $value['0'];
                    } else {
                        $value = $value;
                    }
                    foreach ($value as $okey => $ovalue) {
                        if (!empty($ovalue)) {
                            echo "  - " . trim($okey) . ": " . trim($ovalue) . "\n";
                        }
                    }
                }
            }
        }
        $counter++;
    }
}