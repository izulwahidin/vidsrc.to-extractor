<?php
const DOMAIN = 'https://vidsrc.to';
const DOMAIN2 = 'https://vidplay.online';
try {
    $url = DOMAIN.'/embed/movie/tt10545296';
    $raw = fetch($url);
    file_put_contents('./x',$raw);
    preg_match('~data-id="(.*?)">~',$raw, $dataID);
    $sources = json_decode(fetch(DOMAIN."/ajax/embed/episode/{$dataID[1]}/sources"));
    if($sources->status !== 200) throw new Exception("Error: failed to get sources", 1);
    $sourceGet = json_decode(fetch(DOMAIN."/ajax/embed/source/{$sources->result[0]->id}"));
    if(is_numeric($sourceGet->result)) throw new Exception("Error: failed to get single sources", 1);
    $encodedData = $sourceGet->result->url;
    //  
        // GET https://vidplay.online/e/xxxxxxxxx is NOT POSSIBLE RIGHT NOW
    // 
    $raw2 = fetch(DOMAIN2."/e/51J0O435ZYQ8?sub.info=https%3A%2F%2Fvidsrc.to%2Fajax%2Fembed%2Fepisode%2FofdiPcxh%2Fsubtitles&t=4xjQCvUiDFMKzw%3D%3D&ads=0&src=vidsrc");
    preg_match('~data-id="(?P<id>\d+)" data-p="(?P<p>\d+)" data-cg="(?P<cg>.*?)" >~', $raw2, $datas);
    ['id' => $xid, 'p' => $xp, 'cg' => $xcg] = $datas;
    // 
        // GET https://vidplay.online/mediainfo/xxxxxxxxx is NOT POSSIBLE RIGHT NOW
    // 
    
} catch (\Throwable $th) {
    print_r($th);
}


function fetch($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


    $headers = array();
    $headers[] = 'Referer: https://vidsrc.to/';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception("Error: parse failed".curl_errno($ch), 1);
    }
    curl_close($ch);
    return $result;
}