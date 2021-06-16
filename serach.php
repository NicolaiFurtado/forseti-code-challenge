<?php
/**
 * Created by PhpStorm.
 * User: Nicolai Furtado
 * Date: 15/06/2021
 * Time: 20:30
 */

$pdo = new PDO('mysql:host=localhost; dbname=web-scrapping; charset=utf8', 'root', 'mysql');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

libxml_use_internal_errors(true);
$count = 0;

while($count < 5){
    $content = file_get_contents('https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias?b_start:int='.(30*$count));
    $document = new DOMDocument();
    $document->loadHTML($content);
    $domArticles = $document->getElementById("content-core");
    $articles = $domArticles->getElementsByTagName("article");

    foreach($articles as $article){
        $title = $article->getElementsByTagName("h2");
        $aTag = $title[0]->getElementsByTagName("a");
        $url = $aTag[0]->attributes[1]->value;
        $span = $article->getElementsByTagName("span");

        foreach($span as $s){
            $spanFind = str_replace(["\n", "\t", " "], "", trim($s->textContent));

            preg_match("/([0-9]{2})[\/]([0-9]{2})[\/]([0-9]{4})/", $spanFind, $matches);
            if(isset($matches[0])){
                $date = $matches[0];
            }

            preg_match("/([0-9]{2})[h]([0-9]{2})/", $spanFind, $matches);
            if(isset($matches[0])){
                $time = $matches[0];
            }
        }

        $date = explode("/", $date);
        $time = explode("h", $time);

        $dateTime = mktime((int)$time[0], (int)$time[1], 0, (int)$date[1], (int)$date[0], (int)$date[2]);
        $title = $aTag[0]->textContent;
        $hash = sha1($title.$url.$date.$time);

        $sql = "SELECT id FROM news WHERE hash='{$hash}'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $title = is_null($title) ? "" : mb_convert_encoding($title, mb_detect_encoding($title), "UTF-8");
        $url = is_null($url) ? "" : $url;
        $dateTime = is_null($dateTime) ? "" : date('c', $dateTime);
        $hash = is_null($hash) ? "" : $hash;

        $countPage = $count + 1;

        if(count($data) == 0) {
            $sql = $pdo->prepare("INSERT INTO news (title, url, dateTime, hash, page) VALUES (:title,:url,:datetime,:hash, :page)");
            $sql->bindParam('title', $title);
            $sql->bindParam('url', $url);
            $sql->bindParam('datetime', $dateTime);
            $sql->bindParam('hash', $hash);
            $sql->bindParam('page', $countPage);
            $sql->execute();
        }
    }
    $count++;
}