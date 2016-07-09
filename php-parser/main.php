<?php
$content=file_get_contents('http://www.kanmx.com/mn/p/xiezhen/');
$content=(str_replace(array("\r","\n","\t","\s",">(*)<"), '', $content));
$patterns = array();
$patterns[0] = '/<[ ]+/si';
$patterns[1] = '/\s(?=\s)/si';
$replacements=array();
$replacements[0] = '<';
$replacements[1] = '';
$content=preg_replace($patterns, $replacements, $content);
/*
$content=preg_replace("/<[ ]+/si","<",$content); //过滤去除<__("<"号后面带空格)
$content = preg_replace('/\s(?=\s)/si', '', $content);
*/
//echo "type=".gettype($content)."<br>";
//echo likepre($content)."<br>";
//echo $content;
//exit;
$match=array();
preg_match_all('/<div .*?class="listbox".*?>(.*?)<\/div>/i', $content, $match);
/*
preg_match('/<div[^>]*id="listbox"[^>]*>(.*?) <\/div>/si',$text,$match); 
*/
//print_r($match[1]);

$content=join("",$match[1]);
/*
preg_match_all('/<li><a href="(.*?)" .*?title="(.*?)" .*?>.*?<\/a><\/li>/i', $content, $match);
*/
preg_match_all('/<li><a href="(.*?)" .*?title="(.*?)" .*?><img.*?<\/li>/i', $content, $match);

/*
preg_match_all('#<img[^>]*>#i', $content, $match);  
*/ 
array_shift($match);

print_r($match);
function likepre ($string) {
$string = str_replace('"','&quot;',$string);
$string = str_replace("'",'&#39;',$string);
$string = str_replace("<","&lt;",$string);
$string = str_replace(">","&gt;",$string);
$string = str_replace("\t","　",$string);                //換為全角空格
$string = str_replace("  ","　",$string);                //兩個半角空格換為全角空格
$string = nl2br($string);
return $string;
}


function strip_whitespace($content) {
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                case T_START_HEREDOC:
                    $stripStr .= "<<<THINK\n";
                    break;
                case T_END_HEREDOC:
                    $stripStr .= "THINK;\n";
                    for($k = $i+1; $k < $j; $k++) {
                        if(is_string($tokens[$k]) && $tokens[$k] == ';') {
                            $i = $k;
                            break;
                        } else if($tokens[$k][0] == T_CLOSE_TAG) {
                            break;
                        }
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}
?>
?>
