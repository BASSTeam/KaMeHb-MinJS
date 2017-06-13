/*
    Author:     Влад KaMeHb Марченко
    Version:    1.0-f
    ToDo:       --
*/
document.addEventListener("DOMContentLoaded", function(){
    var currentDir = null;
    fs = {
        'getCurrentFName' : function(onlyName){
            var fname = null;
            try {
                eval('+');
            } catch(e){
                fname = e.stack.split('.getCurrentFName (')[1].match(/(.*):\d*:\d*/)[1];
                if(onlyName){
                    fname = fs.splitDirName(fname)[1];
                }
            }
            return fname;
        },
        'getCurrentDir' : function(){
            if (currentDir !== null){
                return currentDir;
            } else {
                return fs.splitDirName(fs.getCurrentFName())[0];
            }
        },
        'cd' : function(dir){
            currentDir = dir;
        },
        'splitDirName' : function(str){
            var result = ['',''], tmp = str.split('/');
            result[1] = tmp.pop();
            result[0] = tmp.join('/') + '/';
            if (result[0] == ''){
                result[0] = '/';
            }
            return result;
        }
    };
    function getXmlHttp(){
        var xmlhttp;
        try{
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e){
            try{
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(E){
                xmlhttp = false;
            }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
            xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
    }
    require = function(fname, returnResult = false){
        if(document.querySelector('script[_data-url="' + fname + '"]') == null){
            var xhr = getXmlHttp();
            xhr.open('GET', fname, false);
            xhr.send();
            if (xhr.status != 200){
                console.error('Error loading ' + fname + ' (' + xhr.status + ': ' + xhr.statusText + ')\n\tat ' + fs.getCurrentFName() + ':60:8');
                return false;
            } else {
                if (returnResult) return xhr.responseText;
                var tmp = document.createElement('script');
                tmp.innerHTML = xhr.responseText;
                tmp.setAttribute('_data-url', fname);
                document.head.appendChild(tmp);
                return true;
            }
        } else {
            return true;
        }
    }
});
