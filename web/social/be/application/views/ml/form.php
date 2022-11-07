<?php

    function photoReturnLink($photoInfo, $size = 'med'){
        $relativepath = $photoInfo['relativepath'];
        $relativepath = str_replace('/', '', $relativepath);
        $fullPath = $photoInfo['fullpath'].(!empty($size) ? $size.'_' : '').$photoInfo['name'];
        $fullPath_exist = '../'.$photoInfo['fullpath'].''.(!empty($size) ? $size.'_' : '').$photoInfo['name'];

        if(!file_exists($fullPath_exist))
            $fullPath = '/media/images/unavailable-preview.gif';
        return '../'.$fullPath;
    }
    
    function videoReturnLink($videoInfo, $size = ''){
        $mediaPath = '../'. $videoInfo ['fullpath'].'';
        $videoCode = $videoInfo[ 'code' ];
        $thumbs = glob(  $mediaPath . $videoCode . "*.jpg" );
        if ( $thumbs && count($thumbs) > 0 ){
            $path_parts = pathinfo($thumbs[0]);
            $filename = $path_parts['filename'];
            $relativepath = $videoInfo['relativepath'];
            $relativepath = str_replace('/', '', $relativepath);
            $fullPath = $videoInfo ['fullpath'].(!empty($size) ? $size.'_' : '').$filename.'.jpg';
            return '../'.$fullPath;
        }
        else{
            return '/media/images/unavailable-preview.gif';
        }
    }

$controller = $this->router->class;
$input_span = (isset($input_span)) ? $input_span . ' ' : ''; 
$source = $result['source'];
$url = '';
if(isset($source['hash_id']))
{
    $lang_string = $lang.'/';
    if($lang = 'en')
            $lang_string = "";
    $url = '../'.$lang_string.($source['type'] == 'i'? 'photo' : 'video').'/'.$source['hash_id'];
}
?>
<h3><?=(isset($title) ? $title.": " : '')?><?= $source['hash_id'] ?></h3><a href="ml">Back to list</a>

<table class="table">
    <tr>
        <th colspan="1">Source</th>
        <th colspan="2"></th>
    </tr>
    <tr>
        <td colspan="1">
            <div>
                <?php echo form_label('Title', 'title'); ?>
                <span><?= $source['title'] ?></span>
            </div>
            <div>
                <?php echo form_label('Description', 'description'); ?>
                <span><?= $source['description'] ?></span>
            </div>
            <div>
                <?php echo form_label('Place taken at', 'placetakenat'); ?>
                <span><?= $source['placetakenat'] ?></span>
            </div>
            <div>
                <?php echo form_label('Keywords', 'keywords'); ?>
                <span><?= $source['keywords'] ?></span>
            </div>
            <div>
                <?php echo form_label('Type', 'type'); ?>
                <span><?= $source['type'] == 'i' ? 'Image' : 'Video'; ?></span>
            </div>
        </td>
        <td colspan="2">
            <img style="width: 600px;" src="<?php if($main['image_video'] == 'i') {echo photoReturnLink($main);} else {echo videoReturnLink($main);}?>" >
        </td>
    </tr>
    <tr>
        <th colspan="1" style="background-color: #f8f8f8;">English</th>
        <th colspan="1">Hindu</th>
        <th colspan="1" style="background-color: #f8f8f8;">French</th>
    </tr>
    <tr>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#f8f8f8', 'key' => 'en', 'item' => $result['en'])) ?>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#fff', 'key' => 'in', 'item' => $result['in'])) ?>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#f8f8f8', 'key' => 'fr', 'item' => $result['fr'])) ?>
    </tr>
    <tr>
        <th colspan="1">Chinese</th>
        <th colspan="1" style="background-color: #f8f8f8;">Spanish</th>
        <th colspan="1">Portuguese</th>
    </tr>
    <tr>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#fff', 'key' => 'zh', 'item' => $result['zh'])) ?>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#f8f8f8', 'key' => 'es', 'item' => $result['es'])) ?>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#fff', 'key' => 'pt', 'item' => $result['pt'])) ?>
    </tr>
    <tr>
        <th colspan="1" style="background-color: #f8f8f8;">Italian</th>
        <th colspan="1">Deutsch</th>
        <th colspan="1" style="background-color: #f8f8f8;">Filipino</th>
    </tr>
    <tr>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#f8f8f8', 'key' => 'it', 'item' => $result['it'])) ?>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#fff', 'key' => 'de', 'item' => $result['de'])) ?>
        <?= $this->load->view('ml/video_trans', array('id' => $source['id'], 'bg' => '#f8f8f8', 'key' => 'tl', 'item' => $result['tl'])) ?>
    </tr>
</table>
<iframe height="900px" width="100%" src="<?= $url ?>">


