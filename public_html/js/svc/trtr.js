///* 
// * To change this license header, choose License Headers in Project Properties.
// * To change this template file, choose Tools | Templates
// * and open the template in the editor.
// */
//
//$('.parallax-window').parallax({imageSrc: '/assets/svc/trtr/nebo1.png'});
//
function sec_zerro(x) {
    if (x < 10)
        x = '0' + x;
    return x;
}

function time() {
    var d = new Date();

    var s = (55 - d.getSeconds());
    var m = (59 - d.getMinutes());

    if (s + m === 0) {
        location.reload();
    }

    s = sec_zerro(s);
    m = sec_zerro(m);
 $('#watch').html(m + ':' + s);
//    if (s % 2 === 0) {
//        $('#watch').html(m + ':' + s);
//    } else {
//        $('#watch').html(m + ' ' + s);
//    }
}

function show_line(id) {
    var elems = $(".tr_line");
    var elemsTotal = elems.length;
    for (var i = 0; i < elemsTotal; ++i) {
        $(elems[i]).css('background', 'none');
    }
    $('#' + id).css('background', '#f443365c');
}

window.onload = function () {

    var timerId = setInterval(function () {
        time();
    }, 1000);

    var elems = $(".tnum");

    var elemsTotal = elems.length;
    for (var i = 0; i < elemsTotal; ++i) {
        if (elems[i].innerHTML < 20 || elems[i].innerHTML > 80) {
            $(elems[i]).css('color', '#e91e63');
        } else {
            $(elems[i]).css('color', '#009688');
        }
    }


    var loc = $(".location");

    var locTotal = loc.length;
    var tmp = '';
    for (var i = 0; i < locTotal; ++i) {
        tmp = loc[i].innerHTML;
        if (tmp[0] === "Л") {
            $(loc[i]).css('color', '#e91e63');
        }
    }
    date_map();
    pos_in_map();
};


function date_map() {
    var date = new Date();
    var m = date.getUTCMonth();
    if (m === 11 || m === 0 || m === 1) {
        $('#trtr_map').css('filter','hue-rotate(90deg) contrast(140%)');
        $('#wather').html('-');
    }
    if ( m === 5 || m === 6 || m === 7) {
        $('#trtr_map').css('filter','saturate(200%)');  
        $('#wather').html('+');
        
    }
    if (m === 8 || m === 9 || m === 10) {
        $('#trtr_map').css('filter','saturate(50%)');  
    }
    
    if (m === 2 || m === 3 || m === 4) {
        $('#trtr_map').css('filter','hue-rotate(-60deg)');  
    }
    
    
}

function randomInteger(min, max) {
    var rand = min + Math.random() * (max - min);
    rand = Math.round(rand);
    return rand;
}



function pos_in_map() {
    var h = $('#trtr_map').height();
    var w = $('#trtr_map').width();

    var str = $('#map_param').html().trim();
    str = str.substr(0, str.length - 1) + '}';
    var param = JSON.parse(str);
    var tmp = '';
    for (var k in param) { //k - id, param[k] - position
        switch (param[k]) {
            case '1': //праздник
                $('#' + k).css(
                        {
                            'margin-top': randomInteger(0, h / 2 - 100),
                            'margin-left': randomInteger(0, w / 3 - 100)
                        });
                break;
            case '3': //
                $('#' + k).css(
                        {
                            'margin-top': randomInteger(h / 5, h - h / 4),
                            'margin-left': randomInteger(w / 4, w / 2)
                        });

                break;

            case '2': //
                $('#' + k).css(
                        {
                            'margin-top': randomInteger(h / 2 + 100, h - 200),
                            'margin-left': randomInteger(0, w / 4)
                        });
                tmp = $('#img' + k).attr('src');
                $('#img' + k).attr('src', tmp.replace("standart", "work"));
                break;
            case '4': //
                $('#' + k).css(
                        {
                            'margin-top': randomInteger(0, h / 2 - 100),
                            'margin-left': randomInteger(w * (3 / 4), w - 100)
                        });
                tmp = $('#img' + k).attr('src');
                $('#img' + k).attr('src', tmp.replace("standart", "sad"));

                break;
        }
    }

}



function edit_map(param) {
    var class_map = $('#info_map').attr('class');

    console.log(class_map);

    if (class_map === 'no_info') {
        $('#info_map').attr('class', 'block_info');
        $('#legend_but').html('<span onclick="edit_map()">Я все понял!</span>');
    } else {
        $('#info_map').attr('class', 'no_info');
        $('#legend_but').html('<span onclick="edit_map(\'info\')">Что где?</span>');
    }
}

jQuery(function () {
    function LightSprite(name, urls, start, duration) { // version 0.5

        if (!jQuery("*").is(jQuery(name))) {
            console.error('lsprite error: element ' + name + ' not found');
            return false;
        }

        this.tag = jQuery(name);

        this.urls = urls;
        this.len = urls.length;
        this.start = start;
        this.duration = duration;
        this.step = (duration - this.start) / this.len;

        this.fixed = false;

        this.x = 0;
        this.pos = null;
        this.pos_xy = null;

        this.doFix = function () {
            this.fixed = true;
        };

        this.startPosition = function () {
            if (this.pos === null) {
                this.pos = window.pageYOffset;
            }
        };

        this.addXY = function () {
            if (this.pos_xy === null) {
                var coord = this.tag.get(0).getBoundingClientRect();
                this.pos_xy = coord;
            }
        };

        this.onMonitor = function () {
            return $(document).scrollTop() + $(window).height() > this.tag.offset().top && $(document).scrollTop() - this.tag.offset().top < this.tag.height();
        };

        this.fixedDisposableEffect = function (step_num) {
            if (parseInt(step_num) === parseInt(this.len)) {
                this.tag.css("display", 'none');
            }

            if (parseInt(step_num) === 0) {
                this.tag.css({
                    "position": 'static',
                    "top": this.pos_xy.y,
                    "left": this.pos_xy.x
                });
            } else {
                this.tag.css({
                    "position": "fixed",
                    "top": this.pos_xy.y,
                    "left": this.pos_xy.x
                });
            }
        };

        this.sprite = function () {

            if (this.onMonitor()) {
                this.startPosition();

                var scroll_done = (window.pageYOffset - this.pos);
                var num = 0;

                if (this.duration > scroll_done && this.start < scroll_done) {
                    num = Math.round((scroll_done - this.start) / this.step).toFixed(0);
                    if (this.tag.attr("src") !== this.urls[num]) {
                        this.tag.attr('src', this.urls[num]);
                    }
                    if (this.fixed) {
                        this.addXY();
                        this.fixedDisposableEffect(num);
                    }
                }
            }
        };
    }
    function Anima(name, px) { // version 2

        if (!jQuery("*").is(jQuery(name))) {
            return false;
        }

        this.name = name;
        this.px = px;
        this.t_val = '0, -50px'; // по умолчанию сдвиг наверх на 50px
        this.t_type = 'translate';
        this.trans = false;

        this.anima_prop_x = 0;
        this.anima_sign_x = '+';
        this.anima_prop_y = 0;
        this.anima_sign_y = '+';

        this.pos = undefined;

        this.tag = jQuery(this.name);

        this.back = 15; // смещение фона по умолчанию
        this.opasity = true;
        this.tag.css('opacity', 0);
        this.wtop = jQuery(window).scrollTop();
        this.ttop = jQuery(this.name).offset().top;
        this.win = jQuery(window);
        this.wtop = jQuery(window).scrollTop();

        this.startPosition = function () {
            if (this.pos === undefined) {
                this.pos = window.pageYOffset;
            }
        };
        this.noOpacity = function () {
            this.opasity = false;
            this.tag.css('opacity', 1);
        };

        this.setXY = function (x, y) {
            this.setX(x);
            this.setY(y);
        };

        this.setX = function (val) {
            if (val < 0) {
                this.anima_sign_x = '-';
                val *= -1;
            }
            this.anima_prop_x = val;
        };

        this.setY = function (val) {
            if (val < 0) {
                this.anima_sign_y = '-';
                val *= -1;
            }
            this.anima_prop_y = val;
        };

        this.setVal = function (val) {
            this.t_val = val;
        };

        this.setType = function (type) {
            this.t_type = type;
        };

        this.setBack = function (bg) {
            this.back = bg;
        };

        this.setCss = function (time, type) { // замена времени анимации и ее типа
            if (typeof time === 'undefined') {
                time = '0.7s';
            }
            if (typeof type === 'undefined') {
                type = 'cubic-bezier(.21,.08,.24,.91)';
            }
            this.tag.css({
                "transition-timing-function": type,
                "transition-duration": time,
                "-webkit-transition-timing-function": type,
                "-webkit-transition-duration": time
            });
        };

        this.isVisible = function () {
            return (((this.ttop + this.px) <= this.wtop + this.win.height()) && (this.ttop >= this.wtop));
        };

        this.onMonitor = function () {
            return $(document).scrollTop() + $(window).height() > this.tag.offset().top && $(document).scrollTop() - this.tag.offset().top < this.tag.height();
        }

        this.refresh = function () {
            this.tag = jQuery(this.name);
            this.ttop = jQuery(this.name).offset().top;
            this.wtop = jQuery(window).scrollTop();
        };

        this.setTransform = function (str) {
            this.trans = str;
        };

        this.signal = function () {
            this.refresh();
            var x = this.tag;
            if (!x.prop("shown") && this.isVisible(this.px)) {
                x.prop("shown", true);
                if (this.trans) {
                    this.tag.css('transform', this.trans);
                } else {
                    this.tag.css('transform', this.t_type + '(' + this.t_val + ')');
                }

                if (this.opasity) {
                    this.tag.css('opacity', 1);
                }
            }
        };

        this.go = function () { // старт после загрузки страницы
            var x = this.tag;
            x.prop("shown", true);
            if (this.trans) {
                this.tag.css('transform', this.trans);
            } else {
                this.tag.css('transform', this.t_type + '(' + this.t_val + ')');
            }
            this.tag.css('opacity', 1);

        };

        this.signal_back = function (type) {
            var scrolled = window.pageYOffset || document.documentElement.scrollTop;
            var scrolled_px = 0;
            scrolled_px = (scrolled / this.back);
            this.tag.css('background-position-' + type, scrolled_px + '%');
        };

        this.anima = function (duration) {
            if (this.onMonitor()) {
                this.startPosition();
                if (this.opasity) {
                    this.tag.css('opacity', 1);
                }
                var scroll_done = (window.pageYOffset - this.pos);
                var proc_x = this.anima_prop_x * ((scroll_done - this.px) / duration);
                var proc_y = this.anima_prop_y * ((scroll_done - this.px) / duration);

                if (duration > (scroll_done - this.px) && this.px < scroll_done && (scroll_done - this.px) < this.anima_prop_x) {
                    this.tag.css('transform', this.t_type + '(' + this.anima_sign_x + proc_x + 'px, ' + this.anima_sign_y + proc_y + 'px)');
                }
            }
        };
    }

    var grob = new Anima("#grob", 200);
    grob.setXY(170, -700); //параметры смещения
    
/*
    var b = new Anima("#bg1", 10);
    b.setCss('0.1s'); //0transition-duration: 0.1s;
    b.noOpacity();
*/



    jQuery(window).scroll(function () {
        grob.anima(400); // При скролле 100 пикселей сместить элемент вправо на 500пх и вверх на 100пх
        //b.signal_back('x');
    });


    function color_day() {

        var date = new Date();
        if (date.getHours() > 23 || date.getHours() < 2) {
            
            $('html').css('filter', 'invert(100%)  hue-rotate(180deg)');
            $('html').css('background', 'black');
        }
        if (date.getHours() > 2 && date.getHours() < 4) {
            $('html').css('filter', 'invert(100%)  hue-rotate(90deg)');
            $('html').css('background', 'black');
        }
        if (date.getHours() > 4 && date.getHours() < 7) {
            $('html').css('filter', 'invert(100%)  hue-rotate(0deg)');
            $('html').css('background', 'black');
        }
        
        if (date.getHours() > 23 || date.getHours() < 7) {
            $('#day').attr('src', '/assets/trtr/moon.svg');
            $( ".creators2" ).each(function( index ) {
                $( this ).attr('src', '/assets/trtr/creators/w.png');
            });
            $( ".port2" ).each(function( index ) {
                $( this ).attr('src', '/assets/trtr/creators/w.png');
            });
            $( ".creators1" ).each(function( index ) {
                $( this ).css('opacity', '1');
            });
            $( ".port1" ).each(function( index ) {
                $( this ).css('opacity', '1');
            });
        }
        
        
    }
    color_day();

});





/**
 * demo.js
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2018, Codrops
 * http://www.codrops.com
 */
{
    // Class Menu.
    class Menu {
        constructor(el) {
            this.DOM = {el: el};
            // Open and close ctls.
            this.DOM.openCtrl = this.DOM.el.querySelector('.action--menu');
            this.DOM.closeCtrl = this.DOM.el.querySelector('.action--close');
            this.DOM.openCtrl.addEventListener('click', () => this.open());
            this.DOM.closeCtrl.addEventListener('click', () => this.close());
            this.DOM.openCtrl.addEventListener('mouseenter', () => {
                allowTilt = false;
                tilt.reset()
            });
            this.DOM.openCtrl.addEventListener('mouseleave', () => {
                allowTilt = true;
            });

            // The menu items.
            this.DOM.items = Array.from(this.DOM.el.querySelectorAll('.menu__item'));
            // The total number of items.
            this.itemsTotal = this.DOM.items.length;

            // Custom elements that will be animated.
            this.DOM.mainLinks = this.DOM.el.querySelectorAll('.mainmenu > a.mainmenu__item');
            this.DOM.sidemenuLinks = this.DOM.el.querySelectorAll('.sidemenu span.sidemenu__item-inner');
            this.DOM.menulink = this.DOM.el.querySelector('.menu__item-link');
        }
        // Open the menu.
        open() {
            this.toggle('open');
        }
        // Close the menu.
        close() {
            this.toggle('close');
        }
        toggle(action) {
            if ( this.isAnimating ) return;
            // (dis)allow the main image tilt effect.
            allowTilt = action === 'open' ? false : true;
            this.isAnimating = true;
            // Toggling the open state class.
            this.DOM.el.classList[action === 'open' ? 'add' : 'remove']('menu--open');
            // After all is animated..
            const animationEnd = (pos) => {
                if ( pos === this.itemsTotal-1 ) {
                    this.isAnimating = false;
                }
            };
            // Going through each menu´s item.
            this.DOM.items.forEach((el, pos) => {
                // The inner wrapper.
                const innerEl = el.querySelector('.menu__item-inner');
                // config and inner config will have the starting transform values (when opening) and the end ones (when closing) for both the item and its inner element.
                const config = {};
                const configInner = {};
                // Direction defined in the HTML data-direction.
                // bt (bottom to top) || tb (top to bottom) || lr (left to right) || rl (right to left)
                const direction = el.dataset.direction;
                // Using 101% instead of 100% to avoid rendering problems.
                // In order to create the "reveal" effect, the item slides moves in one direction and its inner element in the opposite direction.
                if ( direction === 'bt' ) {
                    config.y = '101%';
                    configInner.y = '-101%';
                    configInner.x = '0%';
                }
                else if ( direction === 'tb' ) {
                    config.y = '-101%';
                    configInner.y = '101%';
                    configInner.x = '0%';
                }
                else if ( direction === 'lr' ) {
                    config.x = '-101%';
                    configInner.x = '101%';
                }
                else if ( direction === 'rl' ) {
                    config.x = '101%';
                    configInner.x = '-101%';
                }
                else {
                    config.x = '101%';
                    config.y = '101%';
                    configInner.x = '-101%';
                    configInner.y = '-101%';
                }
                
                if ( action === 'open' ) {
                    // Setting the initial values.
                    TweenMax.set(el, config);
                    TweenMax.set(innerEl, configInner);

                    // Animate.
                    TweenMax.to([el,innerEl], .9, {
                        ease: Quint.easeOut,
                        x: '0%',
                        y: '0%',
                        onComplete: () => animationEnd(pos)
                    });
                }
                else {
                    TweenMax.to(el, 0.6, {
                        ease: Quart.easeInOut,
                        x: config.x || 0,
                        y: config.y || 0
                    });
                    TweenMax.to(innerEl, 0.6, {
                        ease: Quart.easeInOut,
                        x: configInner.x || 0,
                        y: configInner.y || 0,
                        onComplete: () => animationEnd(pos)
                    });
                }
            });

            // Show/Hide open and close ctrls.
            TweenMax.to(this.DOM.closeCtrl, 0.6, {
                ease: action === 'open' ? Quint.easeOut : Quart.easeInOut,
                startAt: action === 'open' ? {rotation: 0} : null,
                opacity: action === 'open' ? 1 : 0,
                rotation: action === 'open' ? 180 : 270
            });
            TweenMax.to(this.DOM.openCtrl, action === 'open' ? 0.6 : 0.3, {
                delay: action === 'open' ? 0 : 0.3,
                ease: action === 'open' ? Quint.easeOut : Quad.easeOut,
                opacity: action === 'open' ? 0 : 1
            });

            // Main links animation.
            TweenMax.staggerTo(this.DOM.mainLinks, action === 'open' ? 0.9 : 0.2, {
                ease: action === 'open' ? Quint.easeOut : Quart.easeInOut,
                startAt: action === 'open' ? {y: '50%', opacity: 0} : null,
                y: action === 'open' ? '0%' : '50%',
                opacity: action === 'open' ? 1 : 0
            }, action === 'open' ? 0.1 : -0.1);

            // Sidemenu links animation.
            TweenMax.staggerTo(this.DOM.sidemenuLinks, action === 'open' ? 0.5 : 0.2, {
                ease: action === 'open' ? Quint.easeInOut : Quart.easeInOut,
                startAt: action === 'open' ? {y: '100%'} : null,
                y: action === 'open' ? '0%' : '100%'
            }, action === 'open' ? 0.05 : -0.05);

            // The "Learn how to participate" menu link.
            TweenMax.to(this.DOM.menulink, action === 'open' ? 0.9 : 0.6, {
                ease: action === 'open' ? Quint.easeOut : Quart.easeInOut,
                startAt: action === 'open' ? {x: '10%'} : null,
                x: action === 'open' ? '0%' : '10%'
            });
        }
    }
	// Initialize the Menu.
    const menu = new Menu(document.querySelector('nav.menu'));

    // Preload images.
    imagesLoaded(document.querySelector('.background'), {background: false}, () => document.body.classList.remove('loading'));
    
    // extra stuff..
    
    // From http://www.quirksmode.org/js/events_properties.html#position
    // Get the mouse position.
	const getMousePos = (e) => {
        let posx = 0;
        let posy = 0;
		if (!e) e = window.event;
		if (e.pageX || e.pageY) 	{
			posx = e.pageX;
			posy = e.pageY;
		}
		else if (e.clientX || e.clientY) 	{
			posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
			posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
		}
		return { x : posx, y : posy }
    };

    // Main image  tilt effect.
  

    let allowTilt = true;
    const tilt = new TiltFx();

    // Hovering the github link zooms in the main image.
    const githubEl =  document.querySelector('.github');
    githubEl.addEventListener('mouseenter', () => {
        allowTilt = false;
        tilt.zoom()
    });
    githubEl.addEventListener('mouseleave', () => allowTilt = true);
}
