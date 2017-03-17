    var maakmegek = true;


    function addListener(element, eventName, handler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, handler, false);
        }
        else if (element.attachEvent) {
            element.attachEvent('on' + eventName, handler);
        }
        else {
            element['on' + eventName] = handler;
        }
    }

    
    function domaakmegek(trueorfalse) {

        //console.log('domaakmegek ' + maakmegek);
          
        if ( trueorfalse) {
            var blinks = document.getElementsByTagName('blink');
            for (var i = blinks.length - 1; i >= 0; i--) {
                var s = blinks[i];
                s.style.visibility = (s.style.visibility === 'visible') ? 'hidden' : 'visible';
            }
            window.setTimeout(doblink, 1000);
        }

    }

    function doblink() {
        domaakmegek(maakmegek);
        //console.log('ja ' + maakmegek);


        var elementExists = document.getElementById("keuzebutton");

        if ( elementExists === null ) {

            var node = document.createElement("button");                
            node.id = "keuzebutton";
            node.style.float = "right";
            var textnode = document.createTextNode("aargh, hou op!");    
            node.appendChild(textnode);                             
            document.getElementById("blinker").appendChild(node);  
            addListener(node, 'click', switchblink);
    
        } 
        
    } 

        
    function switchblink() {

        if ( maakmegek ) {
            maakmegek = false;
            this.innerHTML = 'Maak me gek!';
                
            var nanana = document.createElement("span");                
            var textnode = document.createTextNode("Kijk, dus het kan wel...");    
            nanana.appendChild(textnode);                             
            nanana.id = "nanana";
            document.getElementById("blinker").appendChild(nanana);  
            
        }
        else {
            maakmegek = true;
            this.innerHTML = 'Aargh, laat het stoppen';
            
            document.getElementById("blinker").removeChild(document.getElementById("nanana"));
        }
        domaakmegek(maakmegek);            
    } 
    
    if (document.addEventListener) document.addEventListener("DOMContentLoaded", doblink, false);
    else if (window.addEventListener) window.addEventListener("load", doblink, false);
    else if (window.attachEvent) window.attachEvent("onload", doblink);
    else window.onload = doblink;


//    function removeListener(element, eventName, handler) {
//        if (element.addEventListener) {
//            element.removeEventListener(eventName, handler, false);
//        }
//        else if (element.detachEvent) {
//            element.detachEvent('on' + eventName, handler);
//        }
//        else {
//            element['on' + eventName] = null;
//        }
//    }

