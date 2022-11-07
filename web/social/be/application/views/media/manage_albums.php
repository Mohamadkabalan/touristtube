<p style="margin:0;">
    <div style="height: 50px;">
        <h1 style="float: left;"><?= $title?>   </h1>
        <select style="float: left; font-size: 15px; margin-top: 23px; margin-bottom: 10px; margin-left: 10px;" id="ownerType">
            <option value="1" selected>Tubers</option>
            <option value="2">Channels</option>
        </select>
        <select style="float: left; font-size: 15px; margin-top: 23px; margin-bottom: 10px; margin-left: 10px;" id="mediaStatus">
            <option value="1">Pending</option>
            <option value="2">Accepted</option>
        </select>
    </div>
</p>
<div>
    <div style="float: left; width: 75%;">
        <div id="albumsContainer">
            <?= isset($albums) ? $this->load->view('media/albums_list') : ''; ?>
        </div>
    </div>
</div>
