jQuery(document).ready(function(){
    sk_igallerys = new SkIGallerys();
    sk_igallerys.init();
});

function SkIGallerys(){
    
    this.init = function(){                
        jQuery('.iGalWrapper').each(function(indx){
            new iGallery(jQuery(this), jQuery(this).find('.iGalleryContainer'));
        });        
    }    
    
    /* iGallery
    ================================================== */    
    function iGallery(igalWrapper, galleryContainer){
        
        var iGalMainPreloader = igalWrapper.find('.galleryPreloader');
        jQuery(window).resize(function(){
            try{
                resizeHandler();
            }catch(e){}
        });        
        var backButton = igalWrapper.find('.iGalBackBTN');
        backButton.css('visibility', 'hidden');
        backButton.click(function(e){
            e.preventDefault();
            closeGallery();
        });        
        var randomRotations = new Array(0, 5, 7, 10, 13, -13, -10, -7, -5, 0);
        var randomHoverPositions = new Array(0, 15, 30, 45, 60, -60, -45, -30, -15, 0);
        
        var thumbW = parseFloat(galleryContainer.attr('data-wdt'));
        var thumbH = parseFloat(galleryContainer.attr('data-hgt'));     
        
        var gapOne = parseFloat(galleryContainer.attr('data-gap_one'));
        var gapTwo = parseFloat(galleryContainer.attr('data-gap_two'));
        
        var data_group_color = galleryContainer.attr('data-group_color');
        var data_lightbox_colors = galleryContainer.attr('data-lightbox_colors');
        
        var showGroupsCaptions;        
        (galleryContainer.attr('data-show_groups_labesls')=='ON')?showGroupsCaptions=true:showGroupsCaptions=false;
        
        var openedGallery;                       
        var galleries = [];
        var countLoadedGallery;
        var countLoadedThumb;
        var galleriesLoaded = false;
        initGalleries();
        //init galleries
        function initGalleries(){
            galleries = new Array();            
            galleryContainer.children().each(function(index){
                var gallery=new Array();                
                jQuery(this).children('div').each(function(index){
                    var largeImage = jQuery(this).attr('data-full_url');
                    var thumbURL = jQuery(this).attr('data-thub_url');                    
                    var caption = jQuery(this).html();                    
                    var groupLabelUI = jQuery('<p class="iGalGroupLabel">'+jQuery(this).attr('data-groupName')+'</p>');
                    groupLabelUI.css('color', '#'+data_group_color);
                    (!showGroupsCaptions)?groupLabelUI.css('display', 'none'):null;                           
                    gallery.push({thumbURL: thumbURL, img: null, groupLabel: groupLabelUI, imgURL: largeImage, itemDescription: caption, thumbItem: null, thumbRotation: 0, thumbHoverRotation: 0, thumbHoverPositionX: 0, thumbHoverPositionY: 0, px: 0, py:0, isHoverEvent: false, isClickEvent: false});            
                });
                galleries.push(gallery);
                jQuery(this).remove();            
            });
            countLoadedGallery=-1;
            loadGalleries();
        }
        
        //load galleries
        function loadGalleries(){
            countLoadedGallery++;
            countLoadedThumb=-1;
            if(countLoadedGallery<galleries.length){
                loadThumb();   
            }else{                         
                galleriesLoaded = true;
                try{ 
                    iGalMainPreloader.remove();
                    iGalMainPreloader = null;
                }catch(e){}               
                addGalleries();
            }        
        }
        
        //load thumb
        function loadThumb(){
            countLoadedThumb++;
            if(countLoadedThumb<galleries[countLoadedGallery].length){       
                var axjReq = new JQueryAJAX();
                axjReq.loadImage(galleries[countLoadedGallery][countLoadedThumb].thumbURL, function(img){
                   //thumb loaded
                   img.addClass('iThumb');
                   img.css('width', thumbW+'px');
                   img.css('height', thumbH+'px');                                    
                   galleries[countLoadedGallery][countLoadedThumb].thumbItem = img;
                   loadThumb();
                });                                    
            }else{
                //console.log('finish load gallery');
                loadGalleries();
            }
        }
        
        function resizeHandler(){
            if(galleriesLoaded && !galleryIsOpen && !galleryMovement){
                addGalleries();
            }
            if(galleryIsOpen){
                displayOpenedGallery(openedGallery, true);
            }             
        } 
        
        
        //display galleries
        function addGalleries(){
            removeGalleries();
            for(var i=0;i<galleries.length;i++){
                try{
                    galleries[i][0].groupLabel.css('opacity', 0);
                    galleries[i][0].groupLabel.appendTo(galleryContainer);
                }catch(e){}                
                for(var j=0;j<galleries[i].length;j++){
                    var item = galleries[i][j].thumbItem;
                    item.css('display', 'block');                      
                    item.appendTo(galleryContainer);
                    item.css('opacity', 0);
                    TweenMax.to(item, .3, {css:{opacity:1}, ease:Power3.EaseIn});
                    TweenMax.to(galleries[i][j].groupLabel, .3, {css:{opacity:1}, ease:Power3.EaseIn});
                }                
            } 
            displayGalleries();       
        }
        
        function removeGalleries(){
            try{
                for(var j=0;j<galleries[i].length;j++){
                    galleries[i].thumbItem.remove();   
                }                
            }catch(e){}
        }
        
        
        var containerWidth;
        var galleryMovement;
        var galleryIsOpen;
        var spacingX = gapOne;
        var spacingY = gapOne;
        var spacingXX = gapTwo;
        var spacingYY = gapTwo;        
        var initialPY = 0;
        var initialPX = 0;
        //display/rearange galleries
        function displayGalleries(){
            containerWidth = galleryContainer.width();
                        
            px = initialPX;
            py = initialPY;
            for(var i=0;i<galleries.length;i++){

                for(var j=0;j<galleries[i].length;j++){
                    var item = galleries[i][j].thumbItem;
                    galleries[i][j].thumbRotation = randomRotations[getRandomInt(0,9)];
                    galleries[i][j].thumbHoverRotation = randomRotations[getRandomInt(0,9)];
                    galleries[i][j].thumbHoverPositionX = randomHoverPositions[getRandomInt(0,9)];
                    galleries[i][j].thumbHoverPositionY = randomHoverPositions[getRandomInt(0,9)];
    
                    
                    galleries[i][j].px = px;
                    galleries[i][j].py = py;                                        
                    
                    item.css('visibility', 'hidden');
                    item.css('cursor', 'pointer');
                    item.data('galleryIndex', i);
                    item.attr('data-galleryIndex', i)
                    item.data('itemIndex', j);
                                                            
                    
                    if(!galleries[i][j].isClickEvent){
                        galleries[i][j].isClickEvent = true;
                        item.click(function(){
                            if(galleryMovement){
                                return;
                            }
                            if(galleryIsOpen){
                                //open lightbox                                
                                openLightbox(jQuery(this), data_lightbox_colors);                        
                            }else{
                                //open gallery
                                if(jQuery(this).data('itemIndex')==galleries[jQuery(this).data('galleryIndex')].length-1){
                                    openGallery(jQuery(this));
                                }
                            }
                        });
                    }            
                    
                    if(j==galleries[i].length-1){
                        item.css('visibility', 'visible');
                        galleries[i][j].thumbRotation = 0;
                        if(!galleries[i][j].isHoverEvent){
                            galleries[i][j].isHoverEvent = true;              
                            item.hover(function(){
                                //gallery roll over
                                rollOverGallery(jQuery(this));
                            }, function(){
                                //gallery roll out
                                rollOutGallery(jQuery(this));
                            });
                        }                       
                    }
                    TweenMax.to(item, 0, {css:{rotation:0}});
                    
                                 
                    item.css('left', px+'px');
                    item.css('top', py+'px');
                    galleries[i][j].groupLabel.css('left', extractNumber(item.css('left'))+ (thumbW/2-galleries[i][j].groupLabel.width()/2)+'px' ); 
                    galleries[i][j].groupLabel.css('top', extractNumber(item.css('top'))+thumbH+5+'px'); 
                        //thumbW                                                   
                }
                px=px+thumbW+spacingX;
                               
                if(px+thumbW>containerWidth){
                    px = initialPX;
                    py+=thumbH+spacingY;
                }
                if(i<galleries.length-1){
                    galleryContainer.css('height', py+thumbH+'px');
                }
                if(galleries.length==1){
                    galleryContainer.css('height', py+thumbH+'px');
                }             
            }        
        }               
        
        
        //roll over gallery
        function rollOverGallery(item){
            if(galleryIsOpen || galleryMovement){
                return;
            }
            var gallery = galleries[item.data('galleryIndex')];
            for(var j=0;j<gallery.length;j++){
                var itemUI = gallery[j].thumbItem;
                if(j<gallery.length-1){
                    itemUI.css('visibility', 'visible');                                     
                    TweenMax.to(itemUI, .2, {css:{opacity:1, rotation:gallery[j].thumbRotation, left: gallery[j].px+gallery[j].thumbHoverPositionX, top: gallery[j].py+gallery[j].thumbHoverPositionY}, ease:Power4.EaseIn});                    
                }
            }
        }
        
        //roll out gallery
        function rollOutGallery(item){
            if(galleryIsOpen || galleryMovement){
                return;
            }        
            var gallery = galleries[item.data('galleryIndex')];
            for(var j=0;j<gallery.length;j++){
                var itemUI = gallery[j].thumbItem;
                if(j<gallery.length-1){
                    TweenMax.to(itemUI, .2, {css:{opacity:0, rotation: 0, left: gallery[j].px, top: gallery[j].py}, ease:Power4.EaseIn, onComplete: function(){
                        jQuery(this).css('visibility', 'hidden');
                    }});
                    
              
                }
            }
        }
        
        
        //open selected gallery
        function openGallery(item){
        galleryContainer.children('.iGalGroupLabel').each(function(indx){
            jQuery(this).remove();
        });            
            galleryMovement = true;        
            var galleryIndex = item.data('galleryIndex');
    
            var gallery = galleries[galleryIndex];
            for(var i=0;i<galleries.length;i++){
                var tempGallery = galleries[i];
                var tempItem;
                if(galleryIndex==i){
                    //open new gallery
                    
                    //scroll top
                    jQuery('html, body').stop().animate({
                        scrollTop: galleryContainer.offset().top-40
                     }, 400);                    
                     
                     displayOpenedGallery(tempGallery);
                     backButton.css('visibility', 'visible');
                                                                        
                }else{
                    //remove other galleries
                    for(var j=0;j<tempGallery.length;j++){
                        tempItem = tempGallery[j].thumbItem;
                        TweenMax.to(tempItem, .3, {css:{opacity:0}, ease:Power4.EaseIn, onCompleteParams:new Array({itm: tempItem}), onComplete: function(param){
                            try{
                                //param.itm.remove();
                                param.itm.css('display', 'none');
                            }catch(e){}                            
                        }});
                    } 
                }
            }
        }
        
        //display oppened gallery
        function displayOpenedGallery(tempGallery, noAnimation){
            var tempItem;
            openedGallery = tempGallery;
            containerWidth = galleryContainer.width();               
            px = initialPX;
            py = initialPY;
                    for(var j=0;j<tempGallery.length;j++){
                        tempItem = tempGallery[j].thumbItem;
                        if(noAnimation){
                            tempItem.css('left', px+'px');
                            tempItem.css('top', py+'px');
                        }else{                            
                            TweenMax.to(tempItem, .3, {css:{left:px, top: py, rotation: 0}, ease:Power4.EaseIn, onComplete: function(){
                                galleryIsOpen = true;
                                galleryMovement = false;                                 
                            }});                            
                        }                        
                        px+=thumbW+spacingXX;
                        if(px+thumbW>containerWidth){
                            px = initialPX;
                            py+=thumbH+spacingYY;
                        }
                        if(j<tempGallery.length-1){
                            galleryContainer.css('height', py+thumbH+'px');
                         }                                                               
                    }        
        }
        
        
        //close current gallery
        function closeGallery(){
            containerWidth = galleryContainer.width();
            backButton.css('visibility', 'hidden');
            galleryMovement = true;
            for(var i=0;i<openedGallery.length;i++){
                var item = openedGallery[i].thumbItem;
                TweenMax.to(item, .3, {css:{rotation: openedGallery[i].thumbRotation, left:containerWidth/2-thumbW/2, top: spacingY*3, opacity: 0}, ease:Power4.EaseIn, onComplete: function(){
                    galleryMovement = false;
                    galleryIsOpen = false;
                    //jQuery('.iThumb').css('opacity', 1);
                    jQuery('.iThumb').removeClass('iThumbHidden');
                    addGalleries();                    
                }}); 
            }
        }                        
        
        var lightboxUI;
        //open lightbox
        function openLightbox(item, controls_color){
            var galleryIndex = item.data('galleryIndex');
            var index = item.data('itemIndex');
            var gallery = galleries[galleryIndex];
 
            lightboxUI = new iGalleryLightbox(gallery, index, controls_color);       
        }        
                        
        
        
        /**
         * utils
         */
        function getRandomInt (min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
        //extract number
        function extractNumber(pxValue){
            var striped = pxValue.substring(0, pxValue.length-2);
            var val = parseFloat(striped);
            return val;
        }        
                              
                
    }    
    /* end iGallery
    ================================================== */
   
   
    /* iGallery lightbox
    ================================================== */   
   function iGalleryLightbox(original_gallery, index, controls_color){
       var _buttons_colors = (controls_color!=undefined)?controls_color:'ec761a';
       var gallery = new Array();
       for(var i=0;i<original_gallery.length;i++){
           gallery.push(original_gallery[i]);
       }
              
       
       var tmpl = ''+
       '<div id="sk_igallery_lightbox">'+
       '</div>';
       var preloaderTmpl = '<div class="iGalleryPreloader"><img src="'+IGALLERY_DTA_FRONT.IMAGES_URL+'/preloader.gif" alt="preloader" /></div>';
       
       var containersTmpl = ''+
       '<p id="ilghtb_caption"></p>'+
       '<div id="ilghtb_img"></div>'+
       '<div id="ilghtb_controls">'+
            '<a href="#" class="genericBoxButtonLightbox ifloatRight lightBxRight" style="margin-left: 1px; background-color: #'+_buttons_colors+';"><img src="'+IGALLERY_DTA_FRONT.IMAGES_URL+'/right.png" alt="" /></a>'+
            '<a href="#" class="genericBoxButtonLightbox ifloatRight lightBxClose" style="margin-left: 1px; background-color: #'+_buttons_colors+';"><img src="'+IGALLERY_DTA_FRONT.IMAGES_URL+'/close.png" alt="" /></a>'+
            '<a href="#" class="genericBoxButtonLightbox ifloatRight lightBxLeft" style="background-color: #'+_buttons_colors+';"><img src="'+IGALLERY_DTA_FRONT.IMAGES_URL+'/left_arrow.png" alt="" /></a>'+
        '</div>'+
       '';
       
       var secure_scr_tmpl = '<div id="isecure_screen"></div>';
       
    var leftBtn;
    var closeBtn;
    var rightBtn;
           
       var lgthBox;
       var iPreloader;
       var initialH = 70;
       var preloaderSize = 28;
       var currentIndex = index;
       
       var secure_UI;
       show();    
       function show(){
           secure_UI = jQuery(secure_scr_tmpl);   
           secure_screen(true);                  
           lgthBox = jQuery(tmpl);
           lgthBox.css('top', jQuery(window).height()/2+'px');
           lgthBox.appendTo('body');
           iPreloader = jQuery(preloaderTmpl);
           iPreloader.css('opacity', 0);
           TweenMax.to(iPreloader, 0, {css:{scale:0}});
           iPreloader.appendTo(lgthBox);
           jQuery(containersTmpl).appendTo(lgthBox); 
           jQuery('#ilghtb_controls').css('opacity', 0);         
           lightBoxResize();
           TweenMax.to(lgthBox, .3, {css:{opacity:1, height: initialH, top: (jQuery(window).height()/2-initialH/2)}, delay: .2, ease:Power3.EaseIn, onComplete: function(){
               lightBoxResize();
               TweenMax.to(iPreloader, .2, {css:{opacity:1, scale: 1}, ease:Power3.EaseIn, onComplete: function(){
                   loadImage(imageFirstTime);
               }});
           }});
           
            leftBtn = lgthBox.find('.lightBxLeft');
            closeBtn = lgthBox.find('.lightBxClose');
            rightBtn = lgthBox.find('.lightBxRight');
            
            buttonsHover();
            buttonsClick();                      
       }
       
       
        function buttonsHover(){
            leftBtn.hover(function(e){
                if(!leftValid){return;}
                    TweenMax.to(jQuery(this), .1, {css:{opacity:.8}, ease:Power3.easeIn});
                }, function(e){
                    if(!leftValid){return;}
                    TweenMax.to(jQuery(this), .1, {css:{opacity:1}, ease:Power3.easeIn});
                }); 
            closeBtn.hover(function(e){
                    TweenMax.to(jQuery(this), .1, {css:{opacity:.8}, ease:Power3.easeIn});
                }, function(e){
                    TweenMax.to(jQuery(this), .1, {css:{opacity:1}, ease:Power3.easeIn});
                }); 
            rightBtn.hover(function(e){
                if(!rightValid){return;}
                    TweenMax.to(jQuery(this), .1, {css:{opacity:.8}, ease:Power3.easeIn});
                }, function(e){
                    if(!rightValid){return;}
                    TweenMax.to(jQuery(this), .1, {css:{opacity:1}, ease:Power3.easeIn});
                });                         
        }
        
        function buttonsClick(){
            leftBtn.click(function(e){
                e.preventDefault();
                if(!leftValid){
                    return;
                }
                currentIndex--;
                initopenNewImage();
            });
            closeBtn.click(function(e){
                e.preventDefault();
                closeLightbox();
            });
            rightBtn.click(function(e){
                e.preventDefault();
                if(!rightValid){
                    return;
                }
                currentIndex++;
                initopenNewImage();
            });                
        }
        
        
        function closeLightbox(){           
            //secure_screen.secure(true);
            TweenMax.to(currentImage, .4, {css:{opacity: 0}, ease:Power3.easeIn});
            TweenMax.to(jQuery('#ilghtb_caption'), .4, {css:{opacity: 0}, delay: .2, ease:Power3.easeIn});
            TweenMax.to(jQuery('#ilghtb_controls'), .4, {css:{opacity: 0}, delay: .2, ease:Power3.easeIn});
            

            TweenMax.to(lgthBox, .5, {css:{height: '2px'}, delay: .6, ease:Power3.easeIn});
            TweenMax.to(lgthBox, .5, {css:{top: jQuery(window).height()/2+'px'}, delay: .6, ease:Power3.easeIn, onComplete: lightboxClosed});                
        }        
        
        function lightboxClosed(){
            //secure_screen.secure(false);
            TweenMax.to(lgthBox, .2, {css:{height: '0px'}, ease:Power3.easeIn, onComplete: gcc_lgtbox});
        }
        
        function gcc_lgtbox(){
            isActive = false;
            try{
                lgthBox.empty();
                lgthBox.remove();
                lgthBox = null;
                gallery = null;
                secure_UI.remove();
                secure_UI = null;
            }catch(e){}
        } 
        
           function secure_screen(val){
               if(val){
                   try{
                      secure_UI.remove(); 
                   }catch(e){}
                   secure_UI.appendTo('body');
               }else{
                   secure_UI.remove();
               }
           }               
        
        function initopenNewImage(){
            //secure_screen.secure(true);
            validateButtons();
            TweenMax.to(iPreloader, 0, {css:{opacity: 0, scale: 0}});
            TweenMax.to(currentImage, .4, {css:{opacity: 0}, ease:Power3.easeIn});
            TweenMax.to(iPreloader, .2, {css:{opacity: 1, scale: 1}, delay: .4, ease:Power3.easeIn, onComplete: loadNewImage});
            //preloaderGIF
        }  
    
        function loadNewImage(){
            try{            
                currentImage.remove();
            }catch(e){}
            loadImage(newImageLoaded);
        }
        
        function newImageLoaded(img){
            if(gallery[currentIndex].img==null){
                gallery[currentIndex].img = img;             
            }
            TweenMax.to(iPreloader, .3, {css:{scale: 0, opacity: 0}, ease:Power3.easeIn});
            show_lgtb_image();      
        }                          
        
        var leftValid = false;
        var rightValid = false;
        function validateButtons(){
            leftBtn.css('visibility', 'visible');
            rightBtn.css('visibility', 'visible');
            leftBtn.removeClass('iLightboxDisabledButton');
            rightBtn.removeClass('iLightboxDisabledButton');
            leftBtn.css('background-color', '#'+_buttons_colors);
            rightBtn.css('background-color', '#'+_buttons_colors);            
            leftValid = true;
            rightValid = true;
            if(currentIndex==0){
                //leftBtn.css('visibility', 'hidden');
                //leftBtn.removeClass('portfolioButtonColor');
                leftBtn.addClass('iLightboxDisabledButton');
                leftBtn.css('background-color', '#'+IGALLERY_DTA_FRONT.BTN_COLOR_OFF);
                leftValid = false;
            }
            if(currentIndex==gallery.length-1){
                //rightBtn.css('visibility', 'hidden');
                rightBtn.css('background-color', '#'+IGALLERY_DTA_FRONT.BTN_COLOR_OFF);
                rightBtn.addClass('iLightboxDisabledButton');
                rightValid = false;
            }
            if(currentIndex!=gallery.length-1 && currentIndex!=0){
                leftBtn.css('visibility', 'visible');
                rightBtn.css('visibility', 'visible');
                leftBtn.removeClass('iLightboxDisabledButton');
                rightBtn.removeClass('iLightboxDisabledButton');            
                leftValid = true;
                rightValid = true;            
            }                 
    
        }        
               
       
       function imageFirstTime(img){
           if(gallery[currentIndex].img==null){
               gallery[currentIndex].img = img;
           }
           expandLightbox();
       }
       
       function expandLightbox(){
           TweenMax.to(iPreloader, .2, {css:{opacity:0, scale: 0}, ease:Power3.EaseIn});
           TweenMax.to(lgthBox, .4, {css:{height:jQuery(window).height(), top: 0}, delay: .3, ease:Power3.EaseIn, onComplete: function(){
               lgthBox.css('height', '100%');
               show_lgtb_image();
           }});           
       }
       
       var currentImage;
       var isActive;
       function show_lgtb_image(){
            if(gallery[currentIndex].img==undefined || gallery[currentIndex].img == "" || gallery[currentIndex].img ==null){
                //broken image                
                return;
            }
            isActive = true;
            currentImage = gallery[currentIndex].img;
            currentImage.css('position', 'absolute');
            currentImage.css('max-width', '80%');
            currentImage.css('max-height', '80%');
            currentImage.css('opacity', 0);
            currentImage.appendTo(jQuery('#ilghtb_img'));
                currentImage.bind('contextmenu', function(e) {
                    return false;
                });
            jQuery('#ilghtb_caption').css('opacity', 0);
            jQuery('#ilghtb_caption').html(gallery[currentIndex].itemDescription);            
            lightBoxResize();
            TweenMax.to(currentImage, .4, {css:{opacity: 1}, ease:Power3.easeIn});
            TweenMax.to(jQuery('#ilghtb_caption'), .4, {css:{opacity: 1}, delay: .2, ease:Power3.easeIn});
            TweenMax.to(jQuery('#ilghtb_controls'), .4, {css:{opacity: 1}, delay: .2, ease:Power3.easeIn});
            
            validateButtons();
            secure_screen(false);                      
       }
       
        jQuery(window).resize(function(){
             lightBoxResize();            
        });
        
        
        function loadImage(callBack){
            var url = gallery[currentIndex].imgURL;
            var axjReq = new JQueryAJAX();        
            axjReq.loadImage(url, function(img){            
                callBack(img);
            });        
        }        
        
        
        function lightBoxResize(){
            try{
                iPreloader.css('top', lgthBox.height()/2-preloaderSize/2+'px');
                iPreloader.css('left', lgthBox.width()/2-preloaderSize/2+'px');
                
                if(isActive){
                    if(currentImage!=null&&currentImage!=undefined){
                        currentImage.css('left', lgthBox.width()/2-currentImage.width()/2+'px');
                        currentImage.css('top', lgthBox.height()/2-currentImage.height()/2+'px');
                    }                    
                }
                jQuery('#ilghtb_controls').css('left', lgthBox.width()/2-jQuery('#ilghtb_controls').width()/2+'px');            
                
            }catch(e){}
        }       
       
       function gcc_ilightbox(){
           try{
               
           }catch(e){}
       }
   }
    /* end iGallery lightbox
    ================================================== */    
   
   
    function JQueryAJAX(){
        /**
         * load data trough get
         */
        this.getData = function(path, successCallBack, failCallBack){
            jQuery.get(path, function(response){
               //first responce                   
            }).error(function() { failCallBack(); })
            .success(function(response) {
                successCallBack(response);           
            })        
        }
        
        this.loadImage = function(path, successCallBack, failCallBack){
            var _url = path;
            var _im =jQuery("<img />");
            _im.bind("load",function(){ 
                    successCallBack(jQuery(this));
                });
            _im.attr('src',_url);
        }
    }          
        
}
