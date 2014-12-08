/*!
 * jQuery ClassyGradient
 * http://www.class.pm/projects/jquery/classygradient
 *
 * Copyright 2012 - 2013, Class.PM www.class.pm
 * Written by Marius Stanciu - Sergiu <marius@picozu.com>
 * Licensed under the GPL Version 3 license.
 * Version 1.0.0
 *
 */

 (function ($) {
    $.ClassyGradient = function(element, options) {
        var defaults = {
            gradient: '0% #02CDE8,100% #000000',
            width: 300,
            height: 18,
            point: 8,
            orientation: 'vertical',
            target: '',
            tooltip: '0% #feffff,100% #ededed',
            onChange: function () {},
            onInit: function () {}
        }, plugin = this, $element = $(element), element = element, _container, _canvas, $pointsContainer, $pointsInfos, $pointsInfosContent,
            $pointColor, $pointPosition, $spanPointPositionRes, $btnPointDelete, _context, _selPoint, _points = new Array(), tooltip
        plugin.settings = {};
        plugin.__constructor = function () {
            plugin.settings = $.extend({}, defaults, options);
            plugin.update();
            plugin.settings.onInit();
        };
        plugin.update = function () {
            setupPoints();
            setup();
            render();
        };
        plugin.getCSS = function () {
            var cssGradient = '', svgX = '0%', svgY = '100%', webkitDir = 'left bottom', defDir = 'top', ieDir = '0';
            if (plugin.settings.orientation == 'horizontal') {
                svgX = '100%';
                svgY = '0%';
                webkitDir = 'right top';
                defDir = 'left';
                ieDir = '1';
            }
            var svg = '<svg xmlns="http://www.w3.org/2000/svg">' + '<defs>' + '<linearGradient id="gradient" x1="0%" y1="0%" x2="' + svgX + '" y2="' + svgY + '">';
            var ieFilter = "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + _points[0][1] + "', endColorstr='" + _points[_points.length - 1][1] + "',GradientType=" + ieDir + ")";
            var webkitCss = '-webkit-gradient(linear, left top, ' + webkitDir;
            var defCss = '';
            $.each(_points, function (i, el) {
                webkitCss += ', color-stop(' + el[0] + ', ' + el[1] + ')';
                defCss += ',' + el[1] + ' ' + el[0] + '';
                svg += '<stop offset="' + el[0] + '" style="stop-color:' + el[1] + ';" />';
            });
            webkitCss += ')';
            defCss = defCss.substr(1);
            svg += '</linearGradient>' + '</defs>' + '<rect fill="url(#gradient)" height="100%" width="100%" />' + '</svg>';
            svg = base64(svg);
            cssGradient += 'background: url(data:image/svg+xml;base64,' + svg + ');' + '\n';
            cssGradient += 'background: ' + webkitCss + ';\n';
            cssGradient += 'background: ' + '-moz-linear-gradient(' + defDir + ',' + defCss + ');' + '\n';
            cssGradient += 'background: ' + '-webkit-linear-gradient(' + defDir + ',' + defCss + ');' + '\n';
            cssGradient += 'background: ' + '-o-linear-gradient(' + defDir + ',' + defCss + ');' + '\n';
            cssGradient += 'background: ' + '-ms-linear-gradient(' + defDir + ',' + defCss + ');' + '\n';
            cssGradient += 'background: ' + 'linear-gradient(' + defDir + ',' + defCss + ');';
            return cssGradient;
        };
        plugin.getArray = function () {
            return _points;
        };
        plugin.getString = function () {
            var gradientString = '';
            $.each(_points, function (i, el) {
                gradientString += el[0] + ' ' + el[1] + ',';
            });
            gradientString = gradientString.substr(0, gradientString.length - 1);
            return gradientString;
        };
        plugin.setOrientation = function (orientation) {
            plugin.settings.orientation = orientation;
            renderToTarget();
        };
        var setupPoints = function () {
            _points = new Array();
            if ($.isArray(plugin.settings.gradient)) {
                _points = plugin.settings.gradient;
            }
            else {
                _points = getGradientFromString(plugin.settings.gradient);
            }
        };
        var setup = function () {
            $element.html('');
            _container = $('<div class="ClassyGradient"></div>');
            _canvas = $('<canvas class="canvas" width="' + plugin.settings.width + '" height="' + plugin.settings.height + '"></canvas>');
            _container.append(_canvas);
            _context = _canvas.get(0).getContext('2d');
            $pointsContainer = $('<div class="points"></div>');
            $pointsContainer.css('width', (plugin.settings.width) + Math.round(plugin.settings.point / 2 + 1) + 'px');
            _container.append($pointsContainer);
            $pointsInfos = $('<div class="info"></div>');
            $pointsInfos.append('<div class="arrow"></div>');
            _container.append($pointsInfos);
            $pointsInfosContent = $('<div class="content"></div>');
            $pointsInfos.append($pointsInfosContent);
            tooltip = getGradientFromString(plugin.settings.tooltip);
            renderToElement($pointsInfosContent, tooltip);
            $pointsInfosContent.css('color', plugin.settings.tooltipTextColor);
            $pointsInfos.find('.arrow').css('borderColor', 'transparent transparent ' + tooltip[0][1] + ' transparent');
            $element.hover(function () {
                $element.addClass('hover');
            }, function () {
                $element.removeClass('hover');
            });
            $pointColor = $('<div class="point-color"><div style="background-color: #00ff00"></div></div>');
            $pointPosition = $('<span class="point-position">%</span>');
            $btnPointDelete = $('<a href="javascript:" class="delete"></a>');
            $pointsInfosContent.append($pointColor, $pointPosition, $btnPointDelete);
            $element.append(_container);
            $pointColor.ColorPicker({
                color: '#00ff00',
                onSubmit: function (hsb, hex, rgb) {
                    $element.find('.point-color div').css('backgroundColor', '#' + hex);
                    _selPoint.css('backgroundColor', '#' + hex);
                    renderCanvas();
                    renderToTarget();
                },
                onChange: function (hsb, hex, rgb) {
                    $element.find('.point-color div').css('backgroundColor', '#' + hex);
                    _selPoint.css('backgroundColor', '#' + hex);
                    renderCanvas();
                    renderToTarget();
                }
            });
            $(document).bind('click', function () {
                if (!$element.is('.hover')) {
                    $pointsInfos.hide('fast');
                }
            });
            _canvas.unbind('click');
            _canvas.bind('click', function (e) {
                var offset = _canvas.offset(), clickPosition = e.pageX - offset.left;
                clickPosition = Math.round((clickPosition * 100) / plugin.settings.width);
                var defaultColor = '#000000', minDist = 999999999999;
                $.each(_points, function (i, el) {
                    if ((parseInt(el[0]) < clickPosition) && (clickPosition - parseInt(el[0]) < minDist)) {
                        minDist = clickPosition - parseInt(el[0]);
                        defaultColor = el[1];
                    }
                    else if ((parseInt(el[0]) > clickPosition) && (parseInt(el[0]) - clickPosition < minDist)) {
                        minDist = parseInt(el[0]) - clickPosition;
                        defaultColor = el[1];
                    }
                });
                _points.push([clickPosition + '%', defaultColor]);
                _points.sort(sortByPosition);
                render();
                $.each(_points, function (i, el) {
                    if (el[0] == clickPosition + '%') {
                        selectPoint($pointsContainer.find('.point:eq(' + i + ')'))
                    }
                })
            })
        };
        var render = function () {
            initGradientPoints();
            renderCanvas();
            renderToTarget()
        };
        var initGradientPoints = function () {
            $pointsContainer.html('');
            $.each(_points, function (i, el) {
                $pointsContainer.append('<div class="point" style="background-color: ' + el[1] + '; left:' + (parseInt(el[0]) * plugin.settings.width) / 100 + 'px; top:-' + (i * (plugin.settings.point + 2)) + 'px"><div class="arrow"></div></div>');
            });
            $pointsContainer.find('.point').css('width', plugin.settings.point + 'px');
            $pointsContainer.find('.point').css('height', plugin.settings.point + 'px');
            $pointsContainer.find('.point').mouseup(function () {
                selectPoint(this);
            });
            $pointsContainer.find('.point').draggable({
                axis: "x",
                containment: "parent",
                drag: function () {
                    selectPoint(this);
                    renderCanvas();
                    renderToTarget();
                }
            })
        };
        var selectPoint = function (el) {
            _selPoint = $(el);
            var color = $(el).css('backgroundColor'), position = parseInt($(el).css('left'));
            position = Math.round((position / plugin.settings.width) * 100);
            color = color.substr(4, color.length);
            color = color.substr(0, color.length - 1);
            $pointColor.ColorPickerSetColor(rgbToHex(color.split(',')));
            $pointColor.find('div').css('backgroundColor', rgbToHex(color.split(',')));
            $pointPosition.html('Position: ' + position + '%');
            $btnPointDelete.unbind('click');
            $btnPointDelete.bind('click', function () {
                if (_points.length > 1) {
                    _points.splice(_selPoint.index(), 1);
                    render();
                    $element.find('.info').hide('fast');
                }
            });
            var posLeft = parseInt($(el).css('left')) - 30;
            $element.find('.info').css('marginLeft', posLeft + 'px');
            $element.find('.info').show('fast');
        };
        var renderCanvas = function () {
            _points = new Array();
            $element.find('.point').each(function (i, el) {
                var position = Math.round((parseInt($(el).css('left')) / plugin.settings.width) * 100);
                var color = $(el).css('backgroundColor').substr(4, $(el).css('backgroundColor').length - 5);
                color = rgbToHex(color.split(','));
                _points.push([position + '%', color]);
            });
            _points.sort(sortByPosition);
            renderToCanvas();
            plugin.settings.onChange(plugin.getString(), plugin.getCSS(), plugin.getArray());
        };
        var renderToElement = function ($target, gradient) {
            var svgX = '0%', svgY = '100%', webkitDir = 'left bottom', defDir = 'top', ieDir = '0';
            if (($target == _canvas) || (plugin.settings.orientation == 'horizontal')) {
                svgX = '100%';
                svgY = '0%';
                webkitDir = 'right top';
                defDir = 'left';
                ieDir = '1';
            }
            var svg = '<svg xmlns="http://www.w3.org/2000/svg">' + '<defs>' + '<linearGradient id="gradient" x1="0%" y1="0%" x2="' + svgX + '" y2="' + svgY + '">';
            var ieFilter = "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + gradient[0][1] + "', endColorstr='" + gradient[gradient.length - 1][1] + "',GradientType=" + ieDir + ")";
            var webkitCss = '-webkit-gradient(linear, left top, ' + webkitDir;
            var defCss = '';
            $.each(gradient, function (i, el) {
                webkitCss += ', color-stop(' + el[0] + ', ' + el[1] + ')';
                defCss += ',' + el[1] + ' ' + el[0] + '';
                svg += '<stop offset="' + el[0] + '" style="stop-color:' + el[1] + ';" />';
            });
            webkitCss += ')';
            defCss = defCss.substr(1);
            svg += '</linearGradient>' + '</defs>';
            if ($target == $pointsInfosContent) {
                var tooltipRadius = parseInt($pointsInfosContent.css('borderRadius'));
                svg += '<rect fill="url(#gradient)" height="100%" width="100%" rx="' + tooltipRadius + '" ry="' + tooltipRadius + '" />';
            }
            else {
                svg += '<rect fill="url(#gradient)" height="100%" width="100%" />';
            }
            svg += '</svg>';
            svg = base64(svg);
            $target.css('background', 'url(data:image/svg+xml;base64,' + svg + ')');
            $target.css('background', webkitCss);
            $target.css('background', '-moz-linear-gradient(' + defDir + ',' + defCss + ')');
            $target.css('background', '-webkit-linear-gradient(' + defDir + ',' + defCss + ')');
            $target.css('background', '-o-linear-gradient(' + defDir + ',' + defCss + ')');
            $target.css('background', '-ms-linear-gradient(' + defDir + ',' + defCss + ')');
            $target.css('background', 'linear-gradient(' + defDir + ',' + defCss + ')');
        };
        var renderToTarget = function () {
            if (plugin.settings.target != "") {
                var $target = $(plugin.settings.target);
                renderToElement($target, _points);
            }
        };
        var renderToCanvas = function () {
            var gradient = _context.createLinearGradient(0, 0, plugin.settings.width, 0);
            $.each(_points, function (i, el) {
                gradient.addColorStop(parseInt(el[0]) / 100, el[1]);
            });
            _context.clearRect(0, 0, plugin.settings.width, plugin.settings.height);
            _context.fillStyle = gradient;
            _context.fillRect(0, 0, plugin.settings.width, plugin.settings.height);
            plugin.settings.onChange(plugin.getString(), plugin.getCSS(), plugin.getArray())
        };
        var getGradientFromString = function (gradient) {
            var gradientArray = new Array(), _pointsTmp = gradient.split(',');
            $.each(_pointsTmp, function (i, el) {
                var position;
                if ((el.substr(el.indexOf('%') - 3, el.indexOf('%')) == '100') || (el.substr(el.indexOf('%') - 3, el.indexOf('%')) == '100%')) {
                    position = '100%';
                }
                else if (el.indexOf('%') > 1) {
                    position = parseInt(el.substr(el.indexOf('%') - 2, el.indexOf('%')));
                    position += '%';
                }
                else {
                    position = parseInt(el.substr(el.indexOf('%') - 1, el.indexOf('%')));
                    position += '%';
                }
                var color = el.substr(el.indexOf('#'), 7);
                gradientArray.push([position, color]);
            });
            return gradientArray;
        };
        var rgbToHex = function (rgb) {
            var R = rgb[0], G = rgb[1], B = rgb[2];
            function toHex(n) {
                n = parseInt(n, 10);
                if (isNaN(n)) return "00";
                n = Math.max(0, Math.min(n, 255));
                return "0123456789ABCDEF".charAt((n - n % 16) / 16) + "0123456789ABCDEF".charAt(n % 16);
            }
            function cutHex(h) {
                return (h.charAt(0) == "#") ? h.substring(1, 7) : h;
            }
            function hexToR(h) {
                return parseInt((cutHex(h)).substring(0, 2), 16);
            }
            function hexToG(h) {
                return parseInt((cutHex(h)).substring(2, 4), 16);
            }
            function hexToB(h) {
                return parseInt((cutHex(h)).substring(4, 6), 16);
            }
            return '#' + toHex(R) + toHex(G) + toHex(B);
        };
        var sortByPosition = function (data_A, data_B) {
            if (parseInt(data_A[0]) < parseInt(data_B[0])) {
                return -1;
            }
            if (parseInt(data_A[0]) > parseInt(data_B[0])) {
                return 1;
            }
            return 0;
        };
        var base64 = function (input) {
            var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", output = "", chr1, chr2, chr3, enc1, enc2, enc3, enc4, i = 0;
            while (i < input.length) {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                }
                else if (isNaN(chr3)) {
                    enc4 = 64;
                }
                output += keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
            }
            return output;
        };
        plugin.__constructor();
    };
    $.fn.ClassyGradient = function (options) {
        return this.each(function () {
            if (undefined == $(this).data('ClassyGradient')) {
                var plugin = new $.ClassyGradient(this, options);
                $(this).data('ClassyGradient', plugin);
            }
        })
    }
})(jQuery);