var canvas,
        ctx,
        image,
        iMouseX, iMouseY = 1,
        theSelection = null,
        click = false,
        degree = 0,
        select = false, Masking = false, ma = false,
        cropImageSrc = [],
        imageWidth = 0,
        imageHeight = 0,
        originalImageWidth = 0,
        originalImageHeight = 0,
        realImageWidth = 0,
        realImageHeight = 0;
function setSize() {
    console.log(imageWidth + '-' + imageHeight);
    $('#canvas').css({width: imageWidth, height: imageHeight});
    $('#can').css({width: imageWidth, height: imageHeight});
}
function cloneCanvas(oldCanvas) {

    //create a new canvas
    var newCanvas = document.createElement('canvas');
    var context = newCanvas.getContext('2d');

    //set dimensions
    newCanvas.width = oldCanvas.width;
    newCanvas.height = oldCanvas.height;

    //apply the old canvas to the new one
    context.drawImage(oldCanvas, 0, 0);

    //return the new canvas
    return newCanvas;
}
$(document).ready(function () {

    $(document).on('click', '#photo_but', function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
            $("#info_but").removeClass("active");
            $("#edit_info_part").hide();
            $("#edit_photo_part").show();
        }
    });
    $(document).on('click', '#info_but', function () {
        if (!$(this).hasClass("active")) {
            $(this).addClass("active");
            $("#photo_but").removeClass("active");
            $("#edit_info_part").show();
            $("#edit_photo_part").hide();
        }
    });



    var canvas, ctx;
    var image = document.getElementById('imgInside');
    var ImageSrc = image.src;
    //console.log(ImageSrc);

    var filters = $('.effectCon');

    imageWidth = $('.cell').attr('data-ww');
    imageHeight = $('.cell').attr('data-hh');
    originalImageWidth = imageWidth;
    originalImageHeight = imageHeight;
    realImageWidth = $("#imgInside").attr("data-width");
    realImageHeight = $("#imgInside").attr("data-height");


    var originalCanvas = $('<canvas id="canvas">');
    var originalContext = originalCanvas[0].getContext('2d');

    image.onload = function () {
        originalCanvas.attr({
//            width: this.width,
//            height: this.height
            width: imageWidth,
            height: imageHeight
        });
        originalContext.drawImage(this, 0, 0);
//        imageWidth = image.width;
//        imageHeight = image.height;
    };
    var clone = originalCanvas.clone();

    clone[0].getContext('2d').drawImage(image, 0, 0);
    $('.cell').html(clone);

    Caman('#canvas', image.src, function () {
        this.render();
    });
    Caman('#can', image.src, function () {
        this.render();
    });

//    canvas2 = clone;
    setSize();

    if (imageHeight === 0 || imageHeight === 0) {
        var canvasObj = document.getElementById('canvas');
//        var a = $('#canvas').attr('width');
//        var b = $('#canvas').attr('height');
        imageWidth = a.slice(0, a.length - 2);
        imageHeight = b.slice(0, b.length - 2);
    }

    console.log(imageWidth + '-' + imageHeight);
    // apply effects on click
    filters.click(function (e) {

        var f = $(this);
        var effect = $.trim(f.attr('data-effect'));

        parent.$('.upload-overlay-loading-fix').show();
        if (f.hasClass('active')) {
            parent.$('.upload-overlay-loading-fix').hide();
            return false;
        }
        filters.removeClass('active');
        f.addClass('active');

        var clone = originalCanvas.clone();
        clone[0].getContext('2d').drawImage(image, 0, 0);
        $('.cell').html(clone);

        Caman(clone[0], image.src, function () {
            if (effect === 'normal') {
                this.render(function () {
                    parent.$('.upload-overlay-loading-fix').hide();
                });
            }
            if (effect in this) {
                this[effect]();
                this.render(function () {
                    parent.$('.upload-overlay-loading-fix').hide();
                });
            }
            $('.applyRotate').hide();
            $('.applyRotate').next("span").hide();
        });

        // activating download and reset button
        console.log(imageWidth + '-' + imageHeight);

        $('#download').show();
        $('#reset').show();

        $('#canvas').attr({
            width: imageWidth,
            height: imageHeight
        });
        $('#canvas').css({
            width: imageWidth,
            height: imageHeight
        });
        setSize();
        select = false;
//        parent.$('.upload-overlay-loading-fix').hide();

    });
    $(document).on('click', '.rotateRight,.rotateLeft', function (e) {
        e.preventDefault();
        $('.edit_eventdate_button_container').hide();
        if ($(this).attr('data-class') === 'left') {
            if (degree === 0 || degree === 360)
                degree = 270;
            else
                degree = degree - 90;
        }
        else {
            degree += 90;
        }

        if (imageWidth === 0 || imageHeight === 0) {
            var canvasObj = document.getElementById('canvas');
            var a = canvasObj.style.width;
            var b = canvasObj.style.height;
            imageWidth = a.slice(0, a.length - 2);
            imageHeight = b.slice(0, b.length - 2);
            console.log(a + '-' + b);
        }

        var cw = imageWidth, ch = imageHeight, cx = 0, cy = 0;

        if (degree > 360)
            degree = 90;

        //   Calculate new canvas size and x/y coordinates for image
        switch (degree) {
            case 90:
                cw = imageHeight;
                ch = imageWidth;
                cy = imageHeight * (-1);
                break;
            case 180:
                cx = imageWidth * (-1);
                cy = imageHeight * (-1);
                break;
            case 270:
                cw = imageHeight;
                ch = imageWidth;
                cx = imageWidth * (-1);
                break;
        }
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');

        console.log('cw : ' + cw + 'ch : ' + ch);

        $('#canvas').css({
            width: cw,
            height: ch
        });

        //image.src = canvas.toDataURL('image/png');
        canvas.setAttribute('width', cw);
        canvas.setAttribute('height', ch);
        ctx.rotate(degree * Math.PI / 180);
        ctx.drawImage(image, cx, cy, imageWidth, imageHeight);
        //var applyDiv = $('div')
        $('.applyRotate').show();
        $('.applyRotate').next("span").show();
        select = false;
        //$('.applyDiv').show();
    });


    /*
     from here the functionality of crop
     */
    $('.crop').click(function (e) {
        e.preventDefault();
        $('.applyRotate').hide();
        $('.applyRotate').next("span").hide();
        Masking = false;

        if (!select) {
            $('.edit_eventdate_button_container').hide();
            initCrop(200, 200, 200, 200);
            select = true;
        }
        else {
            $('.edit_eventdate_button_container').show();
            //alert('slect true');
            initCrop(0, 0, 0, 0);
            select = false;
        }
    });

    function Selection(x, y, w, h) {
        this.x = x; // initial positions
        this.y = y;
        this.w = w; // and size
        this.h = h;

        this.px = x; // extra variables to dragging calculations
        this.py = y;

        this.csize = 0; // resize cubes size
        this.csizeh = 3; // resize cubes size (on hover)

        this.bHow = [false, false, false, false]; // hover statuses
        this.iCSize = [this.csize, this.csize, this.csize, this.csize]; // resize cubes sizes
        this.bDrag = [false, false, false, false]; // drag statuses
        this.bDragAll = false; // drag whole selection


    }
// define Selection draw method
    Selection.prototype.draw = function () {
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.strokeRect(this.x, this.y, this.w, this.h);

        // draw part of original image
        if (this.w > 0 && this.h > 0) {
            if (this.x > 0 && this.x < $('.cell').attr('data-ww') && this.y > 0 && this.y < $('.cell').attr('data-hh')) {
//                ctx.drawImage(canvas, this.x, this.y, this.w, this.h, this.x, this.y, this.w, this.h);
            }
        }

        // draw resize cubes
        ctx.fillStyle = '#fff';
        ctx.fillRect(this.x - this.iCSize[0], this.y - this.iCSize[0], this.iCSize[0] * 2, this.iCSize[0] * 2);
        ctx.fillRect(this.x + this.w - this.iCSize[1], this.y - this.iCSize[1], this.iCSize[1] * 2, this.iCSize[1] * 2);
        ctx.fillRect(this.x + this.w - this.iCSize[2], this.y + this.h - this.iCSize[2], this.iCSize[2] * 2, this.iCSize[2] * 2);
        ctx.fillRect(this.x - this.iCSize[3], this.y + this.h - this.iCSize[3], this.iCSize[3] * 2, this.iCSize[3] * 2);
    };

    function drawScene() { // main drawScene function

        if (Masking)
            return false;
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height); // clear canvas

        // draw source image
        ctx.drawImage(image, 0, 0, ctx.canvas.width, ctx.canvas.height);
//        ctx.drawImage(originalCanvas[0], 0, 0, ctx.canvas.width, ctx.canvas.height);

        // and make it darker
        ctx.fillStyle = 'rgba(0, 0, 0, 0)';
        ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);

        // draw selection
        theSelection.draw();
    }

    function initCrop(m, n, o, p) {

        //if(!canvas)
        $('#canvas').remove();

        canvas = originalCanvas;
        console.log(imageHeight + '-' + imageWidth);
        ctx = canvas[0].getContext('2d');
        canvas.attr({
            width: imageWidth,
            height: imageHeight
        }).css({
            width: imageWidth,
            height: imageHeight
        });

        $('.cell').html(canvas);
        // create initial selection
        theSelection = new Selection(m, n, o, p);

        $('#canvas').mousemove(function (e) { // binding mouse move event
            var canvasOffset = $(canvas).offset();
            iMouseX = Math.floor(e.pageX - canvasOffset.left);
            iMouseY = Math.floor(e.pageY - canvasOffset.top);

            // in case of drag of whole selector
            if (theSelection.bDragAll) {
                theSelection.x = iMouseX - theSelection.px;
                theSelection.y = iMouseY - theSelection.py;
            }

            for (i = 0; i < 4; i++) {
                theSelection.bHow[i] = false;
                theSelection.iCSize[i] = theSelection.csize;
            }

            // hovering over resize cubes
            if (iMouseX > theSelection.x - theSelection.csizeh && iMouseX < theSelection.x + theSelection.csizeh &&
                    iMouseY > theSelection.y - theSelection.csizeh && iMouseY < theSelection.y + theSelection.csizeh) {

                theSelection.bHow[0] = true;
                theSelection.iCSize[0] = theSelection.csizeh;
            }
            if (iMouseX > theSelection.x + theSelection.w - theSelection.csizeh && iMouseX < theSelection.x + theSelection.w + theSelection.csizeh &&
                    iMouseY > theSelection.y - theSelection.csizeh && iMouseY < theSelection.y + theSelection.csizeh) {

                theSelection.bHow[1] = true;
                theSelection.iCSize[1] = theSelection.csizeh;
            }
            if (iMouseX > theSelection.x + theSelection.w - theSelection.csizeh && iMouseX < theSelection.x + theSelection.w + theSelection.csizeh &&
                    iMouseY > theSelection.y + theSelection.h - theSelection.csizeh && iMouseY < theSelection.y + theSelection.h + theSelection.csizeh) {

                theSelection.bHow[2] = true;
                theSelection.iCSize[2] = theSelection.csizeh;
            }
            if (iMouseX > theSelection.x - theSelection.csizeh && iMouseX < theSelection.x + theSelection.csizeh &&
                    iMouseY > theSelection.y + theSelection.h - theSelection.csizeh && iMouseY < theSelection.y + theSelection.h + theSelection.csizeh) {

                theSelection.bHow[3] = true;
                theSelection.iCSize[3] = theSelection.csizeh;
            }

            // in case of dragging of resize cubes
            var iFW, iFH;
            if (theSelection.bDrag[0]) {
                var iFX = iMouseX - theSelection.px;
                var iFY = iMouseY - theSelection.py;
                iFW = theSelection.w + theSelection.x - iFX;
                iFH = theSelection.h + theSelection.y - iFY;
            }
            if (theSelection.bDrag[1]) {
                var iFX = theSelection.x;
                var iFY = iMouseY - theSelection.py;
                iFW = iMouseX - theSelection.px - iFX;
                iFH = theSelection.h + theSelection.y - iFY;
            }
            if (theSelection.bDrag[2]) {
                var iFX = theSelection.x;
                var iFY = theSelection.y;
                iFW = iMouseX - theSelection.px - iFX;
                iFH = iMouseY - theSelection.py - iFY;
            }
            if (theSelection.bDrag[3]) {
                var iFX = iMouseX - theSelection.px;
                var iFY = theSelection.y;
                iFW = theSelection.w + theSelection.x - iFX;
                iFH = iMouseY - theSelection.py - iFY;
            }

            if (iFW > theSelection.csizeh * 2 && iFH > theSelection.csizeh * 2) {
                theSelection.w = iFW;
                theSelection.h = iFH;

                theSelection.x = iFX;
                theSelection.y = iFY;
            }
            drawScene();
        });

        $('#canvas').mousedown(function (e) { // binding mousedown event
            var canvasOffset = $(canvas).offset();
            iMouseX = Math.floor(e.pageX - canvasOffset.left);
            iMouseY = Math.floor(e.pageY - canvasOffset.top);

            theSelection.px = iMouseX - theSelection.x;
            theSelection.py = iMouseY - theSelection.y;

            if (theSelection.bHow[0]) {
                theSelection.px = iMouseX - theSelection.x;
                theSelection.py = iMouseY - theSelection.y;
            }
            if (theSelection.bHow[1]) {
                theSelection.px = iMouseX - theSelection.x - theSelection.w;
                theSelection.py = iMouseY - theSelection.y;
            }
            if (theSelection.bHow[2]) {
                theSelection.px = iMouseX - theSelection.x - theSelection.w;
                theSelection.py = iMouseY - theSelection.y - theSelection.h;
            }
            if (theSelection.bHow[3]) {
                theSelection.px = iMouseX - theSelection.x;
                theSelection.py = iMouseY - theSelection.y - theSelection.h;
            }
            if (iMouseX > theSelection.x + theSelection.csizeh && iMouseX < theSelection.x + theSelection.w - theSelection.csizeh &&
                    iMouseY > theSelection.y + theSelection.csizeh && iMouseY < theSelection.y + theSelection.h - theSelection.csizeh) {

                theSelection.bDragAll = true;
            }
            for (i = 0; i < 4; i++) {
                if (theSelection.bHow[i]) {
                    theSelection.bDrag[i] = true;
                }
            }
        });

        $('#canvas').mouseup(function (e) { // binding mouseup event
            theSelection.bDragAll = false;
            for (i = 0; i < 4; i++) {
                theSelection.bDrag[i] = false;
            }
            theSelection.px = 0;
            theSelection.py = 0;
        });
        drawScene();
    }

    $('.apply_crop').click(function (e) {
//        e.preventDefault();
        var temp_ctx, temp_canvas;
        temp_canvas = document.createElement('canvas');
        temp_ctx = temp_canvas.getContext('2d');

        if (!theSelection)
            return false;
        if (!theSelection.w || !theSelection.h)
            return false;

        console.log('cropping image..');

        $('.edit_eventdate_button_container').show();
        temp_canvas.width = theSelection.w;
        temp_canvas.height = theSelection.h;
        
        if (theSelection.x > 0 && theSelection.x < $('.cell').attr('data-ww') && theSelection.y > 0 && theSelection.y < $('.cell').attr('data-hh')) {
            temp_ctx.drawImage(originalCanvas[0], theSelection.x, theSelection.y, theSelection.w, theSelection.h, 0, 0, theSelection.w, theSelection.h);

            var vData = temp_canvas.toDataURL();

            cropImageSrc.push(image.src);
            image.src = vData;

            var clone = originalCanvas.clone();

            clone[0].getContext('2d').drawImage(originalCanvas[0], 0, 0);
            $('.cell').html(clone);

            Caman("#canvas", vData, function () {
                this.render(function () {
                    Masking = true;
                });
            });

            // activating download and reset button
            $('#download').show();
            $('#reset').show();
            $('.applyDiv').hide();

            imageWidth = theSelection.w;
            imageHeight = theSelection.h;

            console.log(imageWidth + '-' + imageHeight);

            $('#canvas').attr({
                width: theSelection.w,
                height: theSelection.h
            }).css({
                width: theSelection.w,
                height: theSelection.h
            });
            select = false;
            console.log('Image Cropped.');
        }

    });
    $('#undoCrop').click(function (e) {
        e.preventDefault();
        length = cropImageSrc.length;
        if (length > 0) {
            image.src = cropImageSrc[length - 1];
            //image.onload=function(){
            var clone = originalCanvas.clone();
            clone[0].getContext('2d').drawImage(image, 0, 0);
            $('.cell').html(clone);
            Caman('#canvas', image.src, function () {
                this.render();
            });
            //}
            cropImageSrc.pop();

            // activating download and reset button
            $('#download').show();
            $('#reset').show();
            $('.applyDiv').hide();

            imageWidth = $('.cell').attr('data-ww');
            imageHeight = $('.cell').attr('data-hh');

            if (imageWidth === 0 || imageHeight === 0) {
                var canvasObj = document.getElementById('canvas');
                var a = canvasObj.style.width;
                var b = canvasObj.style.height;
                imageWidth = a.slice(0, a.length - 2);
                imageHeight = b.slice(0, b.length - 2);
                console.log(a + '-' + b);
            }
            $('#canvas').attr({
                width: imageWidth,
                height: imageHeight
            }).css({
                width: imageWidth,
                height: imageHeight
            });
            select = false;

            console.log(imageWidth + '-' + imageHeight);
            console.log('Crop undone.!!');

        } else {
            alert('You haven\'t cropped anything yet');
        }


    });
    $('.reset').click(function () {

        $('.edit_eventdate_button_container').show();
        parent.$('.upload-overlay-loading-fix').show();

        //assigning original image source
        image.src = ImageSrc;

        $('#canvas').remove();
        var clone = originalCanvas.clone();
        clone[0].getContext('2d').drawImage(image, 0, 0);
        $('.cell').html(clone);

        Caman('#canvas', ImageSrc, function () {
            this.render(function () {
                parent.$('.upload-overlay-loading-fix').hide();
            });
        });


        $('#canvas').attr({
            width: originalImageWidth,
            height: originalImageHeight
        }).css({
            width: originalImageWidth,
            height: originalImageHeight
        });

        imageHeight = originalImageHeight;
        imageWidth = originalImageWidth;

        //console.log(imageHeight + '-' + imageWidth);

    });
    $(document).on('click', '#save_photo_edit', function () {
        var theId = $(this).attr("data-id");
        var is_post = $(this).attr("data-post");
        if(typeof is_post === 'undefined' || !is_post)
            is_post = 0; 
        var canvas = document.getElementById('canvas');
        //resultImage = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
        var resultImage = canvas.toDataURL(mediaType);

        var left = $('#imgPhoto').css('left');
        var top = $('#imgPhoto').css('top');
        left = parseInt(left.substring(0, left.length - 2));
        top = parseInt(top.substring(0, top.length - 2));
        left = parseInt(left * $('#imgPhoto').data('ow') / $('#imgPhoto').width());
        top = parseInt(top * $('#imgPhoto').data('oh') / $('#imgPhoto').height());
        top = Math.abs(top);
        left = Math.abs(left);

        $.ajax({
            url: ReturnLink('/ajax/ajax_edit_photo.php'),
            data: {
                image64: resultImage,
                id: theId,
                x: left,
                y: top,
                is_post: is_post
            },
            type: 'post',
            success: function (data) {
                parent.$('.upload-overlay-loading-fix').hide();
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.error == 1) {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                }
                else{
                    setTimeout(function () {
                        parent.window.closeFancyReload(is_album);
                    }, 1000);
                }
            }
        });
    });

    $(document).on('click', '.applyRotate', function () {
        canvas1 = document.getElementById('canvas');
        image.src = canvas1.toDataURL('image/png');
        imageWidth = canvas1.width;
        imageHeight = canvas1.height;
        $('.edit_eventdate_button_container').show();
        //console.log(imageWidth + '-' + imageHeight);
        $(this).hide();
        $(this).next("span").hide();
    });
    $('#cancel').click(function () {
        $('.applyDiv').slideToggle();
        return false;
    });
});