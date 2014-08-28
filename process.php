<?php
require_once 'lib/FeedReader/Simple/autoloader.php';
require_once 'resize_class.php';
function getRSSLocation($html, $location){
    if(!$html or !$location){
        return false;
    }else{
        #search through the HTML, save all <link> tags
        # and store each link's attributes in an associative array
        preg_match_all('/<link\s+(.*?)\s*\/?>/si', $html, $matches);
        $links = $matches[1];
        $final_links = array();
        $link_count = count($links);
        for($n=0; $n<$link_count; $n++){
            $attributes = preg_split('/\s+/s', $links[$n]);
            foreach($attributes as $attribute){
                $att = preg_split('/\s*=\s*/s', $attribute, 2);
                if(isset($att[1])){
                    $att[1] = preg_replace('/([\'"]?)(.*)\1/', '$2', $att[1]);
                    $final_link[strtolower($att[0])] = $att[1];
                }
            }
            $final_links[$n] = $final_link;
        }
        #now figure out which one points to the RSS file
        for($n=0; $n<$link_count; $n++){
            if(strtolower($final_links[$n]['rel']) == 'alternate'){
                if(strtolower($final_links[$n]['type']) == 'application/rss+xml'){
                    $href = $final_links[$n]['href'];
                }
                if(!$href and strtolower($final_links[$n]['type']) == 'text/xml'){
                    #kludge to make the first version of this still work
                    $href = $final_links[$n]['href'];
                }
                if($href){
                    if(strstr($href, "http://") !== false){ #if it's absolute
                        $full_url = $href;
                    }else{ #otherwise, 'absolutize' it
                        $url_parts = parse_url($location);
                        #only made it work for http:// links. Any problem with this?
                        $full_url = "http://$url_parts[host]";
                        if(isset($url_parts['port'])){
                            $full_url .= ":$url_parts[port]";
                        }
                        if($href{0} != '/'){ #it's a relative link on the domain
                            $full_url .= dirname($url_parts['path']);
                            if(substr($full_url, -1) != '/'){
                                #if the last character isn't a '/', add it
                                $full_url .= '/';
                            }
                        }
                        $full_url = $href;
                    }
                    return $full_url;
                }
            }
        }
        return false;
    }
}
function getFile($location){
	$ch = curl_init($location);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

if(isset($_POST)){
	if(isset($_POST['siteUrl'])){		
		$location = $_POST['siteUrl'];
		$html = getFile($location);
		//secho $html;
		echo getRSSLocation($html, $location);
		exit();
	}else if(isset($_POST['feedUrl'])){			
		$feedUrl=$_POST['feedUrl'];
		$feed = new SimplePie();
		$feed->set_feed_url($feedUrl);
		$feed->enable_cache(false);
		$feed->set_output_encoding('Windows-1252');
		$feed->init();
		$items=$feed->get_items();
		//print_r($items);exit();
		//json_encode($feed->get_items());
		//exit();
		//echo "<span><h1>".$feed->get_title()."</h1>";
		//echo "<b>".$feed->get_description()."</b></span><hr />";
		$itemCount=$feed->get_item_quantity();
		$items=$feed->get_items();
		$feedList=array();
		$feedList[0]['main_title']=$feed->get_title();
		$feedList[0]['main_description']=$feed->get_description();
		$counter=1;
		foreach($items as $item)
		{
			$feedList[$counter]['permalink']=$item->get_permalink();
			$feedList[$counter]['title']=$item->get_title();
			$feedList[$counter]['date']=$item->get_date();
			$category=$item->get_category();
			$feedList[$counter]['category']=$category->get_label();
			$description=$item->get_description();
			//Extracing all img tag
			preg_match_all('/<img[^>]+>/i',$description, $result);
			//Extracting src from first img tag
			preg_match_all('/(alt|title|src)=("[^"]*")/i',$result[0][0], $result);
			//Replacing unneccessary content
			$imageUrl=str_replace("src=\"", "", $result[0][0]);
			//Removing last tanging double quotes
			$imageUrl=trim($imageUrl, '"');
			$feedList[$counter]['description']=$description;
			$feedList[$counter]['imageUrl']=$imageUrl;
			//echo '<div><a href="'.$item->get_permalink().'">'.$item->get_title().'</a><br />';
			//echo '<em style="font-size:.7em;color:#666666">'.$item->get_date().'</em>';
			
			//if ($category = $item->get_category())
				//echo "Category: ".$category->get_label();
			
			//echo '<br />';
			//echo $item->get_description().'</div>';
			$counter++;			
		}
		echo json_encode($feedList);
		exit();
		$SiteTitle= $feed->get_title();
		$SiteDesc=$feed->get_description();
		$itemCount=$feed->get_item_quantity();
		$items=$feed->get_items();
		foreach($items as $item)
		{
			$Url=$item->get_permalink();
			$Title=$item->get_title();
			$Desc=$item->get_description();
			$Date=$item->get_date();
			if ($category = $item->get_category())
				$CatName = $category->get_label();
		}
	}
}else{
	header("Location: index.php");
}
?>