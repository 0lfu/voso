<?php
if (!defined('converter-module')) {
    header("location: /browse?e=unauthorized");
    exit;
}
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

function getServiceType($url, $regex_onedrive, $regex_cda) {
    if (preg_match($regex_onedrive, $url)) {
        return "ONEDRIVE";
    } elseif (preg_match($regex_cda, $url)) {
        return "CDA";
    } else {
        return false;
    }
}

function downloadCdaVideo($url)
{
    $yt = new YoutubeDl();
    $yt->setBinPath('youtube-dl/yt-dlp.exe');

    $options = Options::create()
        ->downloadPath('youtube-dl')
        ->url($url)
        ->skipDownload(true);
    $collection = $yt->download($options);

    $videoUrls = array();
    foreach ($collection->getVideos() as $video) {
        if ($video->getError() !== null) {
            return false;
        } else {
            foreach ($video->getFormats() as $formatNode){
                $videoUrls[$formatNode->getFormatId()] = $formatNode->getUrl();
            }
        }
    }
    return $videoUrls;
}

function downloadOnedriveVideo($url) {
    $regex_onedrive = '/.*onedrive\.live\.com.*/';
    $videoUrl = "";
    if (!empty($url)) {
        if (preg_match($regex_onedrive, $url)) {
            $videoUrl = str_replace("/embed?", "/download?", $url);
        }
        else{
            return false;
        }
    }
    return $videoUrl;
}
