<p style="margin:0;">
    <div style="height: 50px;">
        <h1 style="float: left;"><?= $title?>   </h1>
        <select style="float: left; font-size: 15px; margin-top: 23px; margin-bottom: 10px; margin-left: 10px;" id="ownerType">
            <option value="1" selected>Tubers Albums</option>
            <option value="2">Channels Albums</option>
            <option value="3">Tubers Media</option>
            <option value="4">Channels Media</option>
        </select>
        <select style="float: left; font-size: 15px; margin-top: 23px; margin-bottom: 10px; margin-left: 10px;" id="mediaStatus">
            <option value="1">Pending</option>
            <option value="2">Accepted</option>
            <option value="3">Deleted</option>
        </select>
    </div>
</p>
<div>
    <div style="float: left; width: 25%;">
        <div id="albumsContainer">
            <?= $this->load->view('media/albums') ?>
        </div>
    </div>
    <div style="float: left; width: 75%;">
        <div id="otherContainer" style="display: none;">
        <?= isset($media) ? $this->load->view('media/other_media') : ''; ?>
        </div>
        <div id="albumMediaContainer">
            <?= isset($album_media) ? $this->load->view('media/album_media') : ''; ?>
        </div>
    </div>
</div>
