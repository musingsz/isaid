(function(){
    var $D = iTalk.util.Dom,
        $E = iTalk.util.Event;
    
    var domready = function(){
        var h1 = $D.get('j'),
            pos = $D.getXY(h1),
            span = $D.getElementsBy(function(el){return (el.className == 'i' || el.className == 'talk')}, 'span', h1);
        $D.addClass(h1, 'drag');
        h1.style.width = span[0].offsetWidth + span[1].offsetWidth + 32 + 'px';
        var dd = new iTalk.util.DD('j');
        dd.on('endDragEvent', function(){
            var anim = new iTalk.util.Motion(this.getEl(), {points:{to:pos}}, 1, iTalk.util.Easing.elasticOut);
            window.scrollTo(0,0);
            anim.animate();
        }, dd, true);
    }
    
    $E.onDOMReady(domready);
})();
