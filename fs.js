/*
    Name:       FileSystem module for native browser js
    Purpose:    Adds some new functions for file work
    Notice:
                    Functions list:
                        fs.getCurrentFName([onlyName]) - returns a current js file name
                        fs.getCurrentDir() - returns current working directory location
                        fs.changeDir(dir) - allows to change working directory
                        fs.cd(dir) - shortcut for fs.changeDir(dir)
                        fs.splitDirName(str) - returns an array of directory and filename
                        fs.splitLink(link) - splits link for host, port, query, path and protocol pieces and returns it like an standard object
                        require(link) - allows you to require a script like in php and returns true if successfully, or false if not

    Author:     Влад KaMeHb Марченко
    Version:    1.1-f
    Original:   https://raw.githubusercontent.com/BASSTeam/KaMeHb-MinJS/master/fs.js
    ToDo:       --
*/
document.addEventListener("DOMContentLoaded", function(){
    var currentDir = null;
    fs = {
        'getCurrentFName' : function(onlyName = false){
            function native(str){
                var tmp = str.split('at ');
                tmp = tmp[tmp.length - 1].split('(')[1];
                if (tmp == undefined) return false; else return tmp.split(')')[0].match(/(.*):\d*:\d*/)[1];
            }
            var fname = null;
            try {
                eval('+');
            } catch(e){
                fname = native(e.stack);
                if (!fname){
                    fname = location.href;
                }
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
            if(fs.splitDirName(dir)[1] == '')
                currentDir = dir;
            else
                currentDir = dir + '/';
            return currentDir;
        },
        'splitDirName' : function(str){
            var result = ['',''], tmp = str.split('/');
            result[1] = tmp.pop();
            result[0] = tmp.join('/') + '/';
            if (result[0] == ''){
                result[0] = '/';
            }
            return result;
        },
        'splitLink' : function(str){
            var link = document.createElement('a');
            link.setAttribute('href', str);
            var ret = {
                'host'      : link.hostname,
                'port'      : link.port,
                'query'     : link.search.slice(1),
                'path'      : link.pathname,
                'protocol'  : link.protocol.slice(0,-1)
            };
            link = null;
            return ret;
        }
    };
    fs.changeDir = fs.cd;
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
    function absolutePath(fname){
        if (/^\/.*/.test(fname)){
            fname = fs.splitLink(fs.getCurrentDir()).protocol + '://' + fs.splitLink(fs.getCurrentDir()).host + fname;
        }
        if (/^\.\.?\/.*/.test(fname)){
            if (fs.getCurrentDir() == fs.splitLink(fs.getCurrentDir()).protocol + '://' + fs.splitLink(fs.getCurrentDir()).host + '/' && /^\.\.\/.*/.test(fname)) fname = fname.slice(1);
            function absolute(relative){
                var base = fs.getCurrentDir(),
                    stack = base.split("/"),
                    parts = relative.split("/");
                stack.pop();
                for (var i=0; i<parts.length; i++){
                    if (parts[i] == ".")
                        continue;
                    if (parts[i] == "..")
                        stack.pop();
                    else
                        stack.push(parts[i]);
                }
                return stack.join("/");
            }
            fname = absolute(fname);
        }
        return fname;
    }
    require = function(fname, returnResult = false){
        fname = absolutePath(fname);
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
